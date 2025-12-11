<?php

namespace App\Http\Controllers;

use App\Models\Sutartis;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CustomerContractController extends Controller
{
    public function index()
    {
        $contracts = Sutartis::where('pasiraso_id', Auth::user()->id)
            ->with(['paketas', 'paketas.draudimoPolisas'])
            ->orderByDesc('created_at')
            ->paginate(10);

        return view('customer.contracts.index', compact('contracts'));
    }

    public function cancel(Request $request)
    {
        $request->validate([
            'contract_id' => ['required', 'integer', 'exists:sutartis,id'],
        ]);

        $contract = Sutartis::where('id', $request->contract_id)
            ->where('pasiraso_id', Auth::user()->id)
            ->first();

        if (! $contract) {
            return redirect()->route('customer.contracts.index')->with('error', 'Sutartis nerasta arba neturite teisės jos atšaukti.');
        }

        $contract->bukle = 'atsaukta';
        $contract->save();

        return redirect()->route('customer.contracts.index')->with('success', 'Sutartis sėkmingai atšaukta.');
    }
}
