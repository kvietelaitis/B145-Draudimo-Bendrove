<?php

namespace App\Http\Controllers;

use App\Models\DraudimoPolisas;
use App\Models\Prasymas;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PolicyController extends Controller
{
    public function select(Request $request)
    {
        $request->validate([
            'policy_id' => ['required'],
        ]);

        return redirect()->route('policies.packages', ['policy' => $request->input('policy_id')]);
    }

    public function packages($policyId)
    {
        $policy = DraudimoPolisas::with('paketai.paslaugos')->findOrFail($policyId);
        $packages = $policy->paketai;

        return view('policies.packages', compact('policy', 'packages'));
    }

    public function choosePackage(Request $request)
    {
        $validated = $request->validate([
            'paketas_id' => ['required'],
        ]);

        $application = Prasymas::create([
            'data' => now()->toDateString(),
            'bukle' => 'issiustas',
            'vartotojas_id' => Auth::user()->id,
            'paketas_id' => $validated['paketas_id'],
        ]);

        return redirect()->route('customer.dashboard')->with('success', 'Prašymas pateiktas');
    }
}
