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
        Schema::create('nuolaida', function (Blueprint $table) {
            $table->id();
            $table->enum('rusis', ['lojalumas', 'pakvietimas']);
            $table->integer('procentas');
            $table->date('galiojimo_pabaiga');

            $table->foreignId('turi_vartotojas_id')
                ->constrained('vartotojas')
                ->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('nuolaida');
    }
};
