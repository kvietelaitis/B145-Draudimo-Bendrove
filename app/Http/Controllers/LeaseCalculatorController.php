<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class LeaseCalculatorController extends Controller
{
    public function calculate(Request $request)
    {
        $calculation = null;
        $discount = 0;

        $validate = $request->validate([
            'category' => 'required|in:car,machinery,electronics,furniture,housing',
            'initial_cost' => 'required|numeric|min:500|max:500000',
            'market_value' => 'required|numeric|min:500|max:500000',
            'remaining_lease' => 'required|numeric|min:500|max:500000',
            'insurance_type' => 'required|in:full,replacement,damage',
            'years_since_accident' => 'required',
        ]);

        $insuranceRates = [
            'car' => 0.08,
            'machinery' => 0.005,
            'electronics' => 0.02,
            'furniture' => 0.004,
            'housing' => 0.4,
        ];

        $tiers = [
            0 => 0,
            1 => 2,
            2 => 5,
        ];

        $discount = $tiers[$validate['years_since_accident']] ?? 10;

        $insuredValue = max($validate['market_value'], $validate['remaining_lease']);

        $rate = $insuranceRates[$validate['category']];
        $baseCost = $insuredValue * $rate;

        $annualCost = max(0, $baseCost);
        $discountedAnnualCost = $annualCost - ($annualCost * ($discount / 100));
        $monthlyCost = $annualCost / 12;
        $discountedMonthlyCost = $discountedAnnualCost / 12;

        return back()->with([
            'calculation_results' => [
                'baseCost' => round($monthlyCost, 2),
                'annualCost' => round($annualCost, 2),
                'discountedBase' => round($discountedMonthlyCost, 2),
            ],
        ])->withInput(); // Keeps the form filled
    }
}
