<?php

namespace Tests\Feature;

use App\Models\Vartotojas;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UserFlowTest extends TestCase
{
    use RefreshDatabase; // Resets DB after each test

    public function test_user_can_register()
    {
        $response = $this->post('/register-user', [
            'vardas' => 'Jonas',
            'pavarde' => 'Jonaitis',
            'el_pastas' => 'jonas@example.com',
            'slaptazodis' => 'password123',
            'pakvietimo_kodas' => null,
        ]);

        $response->assertRedirect('/');
        $this->assertDatabaseHas('vartotojas', [
            'el_pastas' => 'jonas@example.com',
            'vardas' => 'Jonas',
        ]);
        $this->assertAuthenticated();
    }

    public function test_registration_with_referral_code_creates_discount()
    {
        // Create a referrer
        $referrer = Vartotojas::factory()->create([
            'pakvietimo_kodas' => 'REF123',
        ]);

        $this->post('/register-user', [
            'vardas' => 'Petras',
            'pavarde' => 'Petraitis',
            'el_pastas' => 'petras@example.com',
            'slaptazodis' => 'password123',
            'pakvietimo_kodas' => 'REF123',
        ]);

        // Check if discount was created for referrer
        $this->assertDatabaseHas('nuolaida', [
            'turi_vartotojas_id' => $referrer->id,
            'rusis' => 'pakvietimas',
            'procentas' => 5,
        ]);
    }

    public function test_admin_can_create_worker()
    {
        $admin = Vartotojas::factory()->create(['role' => 'administratorius']);

        $response = $this->actingAs($admin)->post('/register-worker', [
            'worker_name' => 'Vardenis',
            'worker_lastname' => 'Pavardenis',
            'role' => 'darbuotojas',
        ]);

        $response->assertSessionHas('success'); // Password should be in session

        $this->assertDatabaseHas('vartotojas', [
            'vardas' => 'Vardenis',
            'pavarde' => 'Pavardenis',
            'el_pastas' => 'vardenis.pavardenis@draudimas.lt',
            'role' => 'darbuotojas',
        ]);
    }

    public function test_admin_worker_creation_handles_duplicate_emails()
    {
        $admin = Vartotojas::factory()->create(['role' => 'administratorius']);

        // Create first worker
        Vartotojas::create([
            'vardas' => 'Vardenis',
            'pavarde' => 'Pavardenis',
            'el_pastas' => 'vardenis.pavardenis@draudimas.lt',
            'slaptazodis' => 'secret',
            'role' => 'darbuotojas',
        ]);

        // Try to create another worker with same name
        $this->actingAs($admin)->post('/register-worker', [
            'worker_name' => 'Vardenis',
            'worker_lastname' => 'Pavardenis',
            'role' => 'darbuotojas',
        ]);

        // Expect email to have a number appended
        $this->assertDatabaseHas('vartotojas', [
            'el_pastas' => 'vardenis.pavardenis1@draudimas.lt',
        ]);
    }
}
