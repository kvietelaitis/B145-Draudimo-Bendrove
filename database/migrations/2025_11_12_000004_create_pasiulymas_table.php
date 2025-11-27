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
        Schema::create('pasiulymas', function (Blueprint $table) {
            $table->id();
            $table->double('koreguota_kaina');
            $table->enum('pasiulymo_bukle', ['kuriamas', 'issiustas', 'priimtas', 'atmestas'])
                ->default('kuriamas');

            $table->foreignId('gauna_vartotojas_id')
                ->constrained('vartotojas')
                ->onDelete('cascade');

            $table->foreignId('sudaro_vartotojas_id')
                ->nullable()
                ->constrained('vartotojas')
                ->onDelete('set null');

            // New FK: each offer must belong to a draudimo_polisas
            $table->foreignId('draudimo_polisas_id')
                ->constrained('draudimo_polisas')
                ->onDelete('cascade');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pasiulymas');
    }
};
