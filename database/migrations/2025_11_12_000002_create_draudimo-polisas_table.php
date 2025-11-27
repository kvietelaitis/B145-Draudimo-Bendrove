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
        Schema::create('draudimo_polisas', function (Blueprint $table) {
            $table->id();

            $table->string('pavadinimas');
            $table->string('apibudinimas');
            $table->double('bazine_kaina');

            $table->json('salygos')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('draudimo_polisas');
    }
};
