<?php

namespace App\Http\Controllers;

use App\Models\IvykioTipas;
use App\Models\Ivykis;
use App\Models\Sutartis;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class WorkerIncidentController extends Controller
{
    public function index(Request $request)
    {
        $startQuery = Ivykis::with(['tipas', 'nuotraukos', 'vartotojas']);

        if ($request->filled('name')) {
            $name = $request->input('name');

            $startQuery->whereHas('vartotojas', function ($q) use ($name) {
                $q->where('vardas', 'like', "%$name%")
                    ->orWhere('pavarde', 'like', "%$name%");
            });
        }

        $incidents = $startQuery
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
}
