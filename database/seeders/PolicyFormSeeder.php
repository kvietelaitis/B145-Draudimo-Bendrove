<?php

namespace Database\Seeders;

use App\Models\DraudimoPolisas;
use Illuminate\Database\Seeder;

class PolicyFormSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Example: Update Transport Insurance
        // Use 'like' to find by partial name or exact ID if known
        $transport = DraudimoPolisas::where('pavadinimas', 'like', '%Auto%')->first();
        if ($transport) {
            $transport->update([
                'form_fields' => [
                    ['name' => 'valstybinis_numeris', 'label' => 'Valstybinis numeris', 'type' => 'text', 'required' => true],
                    ['name' => 'marke', 'label' => 'Markė', 'type' => 'text', 'required' => true],
                    ['name' => 'modelis', 'label' => 'Modelis', 'type' => 'text', 'required' => true],
                    ['name' => 'vin_kodas', 'label' => 'VIN kodas', 'type' => 'text', 'required' => true],
                ],
            ]);
        }

        $life = DraudimoPolisas::where('pavadinimas', 'like', '%Gyvyb%')->first();
        if ($life) {
            $life->update([
                'form_fields' => [
                    ['name' => 'vardas', 'label' => 'Asmens Vardas', 'type' => 'text', 'required' => true],
                    ['name' => 'pavarde', 'label' => 'Asmens Pavardė', 'type' => 'text', 'required' => true],
                    ['name' => 'asmens_kodas', 'label' => 'Asmens Kodas', 'type' => 'number', 'required' => true],
                ],
            ]);
        }

        // Example: Update Property Insurance
        $property = DraudimoPolisas::where('pavadinimas', 'like', '%Turto%')->first();
        if ($property) {
            $property->update([
                'form_fields' => [
                    ['name' => 'adresas', 'label' => 'Adresas', 'type' => 'text', 'required' => true],
                    ['name' => 'plotas', 'label' => 'Plotas (m²)', 'type' => 'number', 'required' => true],
                    ['name' => 'statybos_metai', 'label' => 'Statybos metai', 'type' => 'number', 'required' => true],
                ],
            ]);
        }
    }
}
