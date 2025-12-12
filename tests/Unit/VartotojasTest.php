<?php

namespace Tests\Unit;

use App\Models\Vartotojas;
use Carbon\Carbon;
use Tests\TestCase;

class VartotojasTest extends TestCase
{
    public function test_years_since_last_accident_calculation()
    {
        // Case 1: User has a specific accident date 2 years ago
        $user = new Vartotojas;
        $user->paskutinio_incidento_data = Carbon::now()->subYears(2);

        $this->assertEquals(2, $user->getYearsSinceLastAccident());

        // Case 2: User has no accident date, uses created_at (5 years ago)
        $user = new Vartotojas;
        $user->paskutinio_incidento_data = null;
        $user->created_at = Carbon::now()->subYears(5);

        $this->assertEquals(5, $user->getYearsSinceLastAccident());
    }

    public function test_role_checks()
    {
        $admin = new Vartotojas(['role' => 'administratorius']);
        $this->assertTrue($admin->isAdmin());
        $this->assertFalse($admin->isKlientas());

        $client = new Vartotojas(['role' => 'klientas']);
        $this->assertTrue($client->isKlientas());
        $this->assertFalse($client->isDarbuotojas());
    }
}
