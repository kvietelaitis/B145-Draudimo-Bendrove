<?php

namespace Database\Seeders;

use App\Models\Vartotojas;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Vartotojas::create([
            'vardas' => 'Admin',
            'pavarde' => 'User',
            'el_pastas' => 'admin@draudimas.lt',
            'slaptazodis' => bcrypt('admin123'),
            'role' => 'administratorius',
            'lojalumo_metai' => 0
        ]);
    }
}
