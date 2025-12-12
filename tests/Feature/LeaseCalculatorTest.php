<?php

namespace Tests\Feature;

use Tests\TestCase;

class LeaseCalculatorTest extends TestCase
{
    public function test_lease_calculator_validates_input()
    {
        $response = $this->post('/calculate-lease', []);

        $response->assertSessionHasErrors(['category', 'initial_cost', 'market_value']);
    }

    public function test_lease_calculator_returns_correct_calculation()
    {
        // Data for a car with 0 years since accident (0% discount)
        // Rate for car is 0.08
        // Insured value = max(10000, 5000) = 10000
        // Base Cost = 10000 * 0.08 = 800
        // Annual Cost = 800
        // Monthly Cost = 800 / 12 = 66.67

        $data = [
            'category' => 'car',
            'initial_cost' => 10000,
            'market_value' => 10000,
            'remaining_lease' => 5000,
            'insurance_type' => 'full',
            'years_since_accident' => 0,
        ];

        $response = $this->post('/calculate-lease', $data);

        $response->assertRedirect();
        $response->assertSessionHas('calculation_results');

        $results = session('calculation_results');

        $this->assertEquals(66.67, $results['baseCost']);
        $this->assertEquals(800.00, $results['annualCost']);
    }

    public function test_lease_calculator_applies_discount()
    {
        // 2 years since accident -> 5% discount
        // Base Annual = 800
        // Discounted Annual = 800 - (800 * 0.05) = 760
        // Discounted Monthly = 760 / 12 = 63.33

        $data = [
            'category' => 'car',
            'initial_cost' => 10000,
            'market_value' => 10000,
            'remaining_lease' => 5000,
            'insurance_type' => 'full',
            'years_since_accident' => 2,
        ];

        $response = $this->post('/calculate-lease', $data);

        $results = session('calculation_results');

        $this->assertEquals(63.33, $results['discountedBase']);
    }
}
