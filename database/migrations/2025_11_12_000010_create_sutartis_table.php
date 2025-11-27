<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('sutartis', function (Blueprint $table) {
            $table->id();
            $table->double('galutine_kaina');
            $table->date('isigaliojimo_data');
            $table->date('galiojimo_pabaigos_data');
            $table->enum('bukle', ['aktyvi', 'pasibaigus', 'atsaukta'])
                ->default('aktyvi');

            $table->foreignId('pasiraso_id')
                ->nullable()
                ->constrained('vartotojas')
                ->onDelete('set null');

            $table->foreignId('sudaro_id')
                ->nullable()
                ->constrained('vartotojas')
                ->onDelete('set null');

            // New FK: each contract must belong to a draudimo_polisas
            $table->foreignId('paketas_id')
                ->constrained('paketas')
                ->onDelete('cascade');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sutartis');
    }
};
