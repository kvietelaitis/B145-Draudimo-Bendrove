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
        Schema::create('paketas', function (Blueprint $table) {
            $table->id();
            $table->string('pavadinimas');
            $table->string('aprasymas');
            $table->foreignId('draudimo_polisas_id')->references('id')->on('draudimo_polisas');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('paketas');
    }
};
