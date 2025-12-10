<?php

namespace App\Http\Controllers;

use App\Models\IvykioNuotrauka;
use App\Models\IvykioTipas;
use App\Models\Ivykis;
use App\Models\Sutartis;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class IncidentController extends Controller
{
    public function index()
    {
        $incidents = Ivykis::where('vartotojas_id', Auth::user()->id)
            ->with(['tipas', 'nuotraukos'])
            ->orderByDesc('pranesimo_data')
            ->paginate(10);

        return view('incidents.index', compact('incidents'));
    }

    public function createForm()
    {
        $incidentTypes = IvykioTipas::all();
        $contracts = Sutartis::where('pasiraso_id', Auth::user()->id)->with('paketas')->get();

        return view('incidents.create', compact('contracts', 'incidentTypes'));
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

        return redirect()->route('incidents.index')->with('success', 'Įvykis sėkmingai registruotas');
    }
}
