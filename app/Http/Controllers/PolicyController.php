<?php

namespace App\Http\Controllers;

use App\Models\DraudimoPolisas;
use App\Models\Paketas;
use App\Models\Prasymas;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PolicyController extends Controller
{
    public function select(Request $request)
    {
        $request->validate([
            'policy_id' => ['required', 'integer', 'exists:draudimoPolisas,id'],
        ]);

        return redirect()->route('customer.policies.packages', ['policy' => $request->input('policy_id')]);
    }

    public function packages($policyId)
    {
        $policy = DraudimoPolisas::with('paketai.paslaugos')->findOrFail($policyId);
        $packages = $policy->paketai;

        return view('customer.policies.packages', compact('policy', 'packages'));
    }

    public function showForm($packageId)
    {
        $paketas = Paketas::with('draudimoPolisas')->findOrFail($packageId);

        return view('customer.policies.form', compact('paketas'));
    }

    public function submit(Request $request)
    {
        $validated = $request->validate([
            'paketas_id' => ['required', 'integer', 'exists:paketas,id'],
        ]);

        $paketas = Paketas::with('draudimoPolisas')->findOrFail($validated['paketas_id']);
        $fields = $paketas->draudimoPolisas->form_fields ?? [];

        $dynamicRules = [];
        $customMessages = [];
        foreach ($fields as $field) {
            $rules = [];
            if (isset($field['required']) && $field['required']) {
                $rules[] = 'required';
            } else {
                $rules[] = 'nullable';
            }

            // Add type-specific rules
            if (isset($field['type'])) {
                switch ($field['type']) {
                    case 'string':
                        $rules[] = 'string';
                        if (isset($field['max'])) {
                            $rules[] = 'max:'.$field['max'];
                        }
                        break;
                    case 'integer':
                        $rules[] = 'integer';
                        if (isset($field['min'])) {
                            $rules[] = 'min:'.$field['min'];
                        }
                        if (isset($field['max'])) {
                            $rules[] = 'max:'.$field['max'];
                        }
                        break;
                    case 'date':
                        $rules[] = 'date';
                        break;
                    case 'email':
                        $rules[] = 'email';
                        break;
                        // Add more types as needed
                }
            }

            $dynamicRules[$field['name']] = $rules;
        }

        $request->validate($dynamicRules);

        $application = Prasymas::create([
            'data' => now()->toDateString(),
            'bukle' => 'issiustas',
            'vartotojas_id' => Auth::user()->id,
            'paketas_id' => $validated['paketas_id'],
            'objekto_duomenys' => $request->except(['_token', 'paketas_id']),
        ]);

        return redirect()->route('customer.dashboard')->with('success', 'Prašymas pateiktas');
    }
}
