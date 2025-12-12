<?php

namespace Database\Factories;

use App\Models\Vartotojas;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Vartotojas>
 */
class VartotojasFactory extends Factory
{
    protected $model = Vartotojas::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'vardas' => fake()->firstName(),
            'pavarde' => fake()->lastName(),
            'el_pastas' => fake()->unique()->safeEmail(),
            'slaptazodis' => Hash::make('password'), // Default password
            'role' => 'klientas',
            'pakvietimo_kodas' => strtoupper(Str::random(8)),
            'lojalumo_metai' => 0,
            'uzblokuotas' => false,
            'paskutinio_incidento_data' => null,
        ];
    }
}
