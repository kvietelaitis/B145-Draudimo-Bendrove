<?php

namespace App\Http\Controllers;

use App\Models\IvykioNuotrauka;
use App\Models\IvykioTipas;
use App\Models\Ivykis;
use App\Models\Sutartis;
use App\Models\Vartotojas;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class WorkerIncidentController extends Controller
{
    public function index()
    {
        $incidents = Ivykis::with(['tipas', 'nuotraukos', 'vartotojas'])
            ->orderByDesc('pranesimo_data')
            ->paginate(10);

        return view('worker.incidents.index', compact('incidents'));
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
            'incident_type' => ['required'],
            'insurance_contract' => ['required'],
            'incident_date' => ['required'],
            'incident_description' => ['required'],
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
