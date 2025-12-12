<?php

namespace App\Http\Controllers;

use App\Models\IvykioNuotrauka;
use App\Models\IvykioTipas;
use App\Models\Ivykis;
use App\Models\Sutartis;
use App\Models\Vartotojas;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CustomerIncidentController extends Controller
{
    public function index()
    {
        $incidents = Ivykis::where('vartotojas_id', Auth::user()->id)
            ->with(['tipas', 'nuotraukos'])
            ->orderByDesc('pranesimo_data')
            ->paginate(10);

        return view('customer.incidents.index', compact('incidents'));
    }

    public function createForm()
    {
        $incidentTypes = IvykioTipas::all();
        $contracts = Sutartis::where('pasiraso_id', Auth::user()->id)->with('paketas')->get();

        return view('customer.incidents.create', compact('contracts', 'incidentTypes'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'incident_type' => ['required', 'integer', 'exists:ivykio_tipas,id'],
            'insurance_contract' => ['required', 'integer', 'exists:sutartis,id'],
            'incident_date' => ['required', 'date'],
            'incident_description' => ['required', 'string', 'max:1000'],
            'photos.*' => ['nullable', 'image', 'max:5120'], // each photo max 5MB
        ]);

        $incident = Ivykis::create([
            'vartotojas_id' => Auth::user()->id,
            'tipas_id' => $validated['incident_type'],
            'ivykio_data' => $validated['incident_date'],
            'apibudinimas' => $validated['incident_description'],
            'pranesimo_data' => now()->toDateString(),
            'bukle' => 'pateiktas',
        ]);

        $user = Vartotojas::find(Auth::user()->id);

        $user->paskutinio_incidento_data = $incident['ivykio_data'];

        $user->save();

        if ($request->hasFile('photos')) {
            foreach ($request->file('photos') as $photo) {
                $path = $photo->store('incidents', 'public');

                IvykioNuotrauka::create([
                    'failo_vieta' => $path,
                    'failo_pavadinimas' => $photo->getClientOriginalName(),
                    'ivykis_id' => $incident->id,
                ]);
            }
        }

        return redirect()->route('customer.incidents.index')->with('success', 'Įvykis sėkmingai registruotas');
    }
}
