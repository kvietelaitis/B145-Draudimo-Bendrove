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
        Schema::create('vartotojas', function (Blueprint $table) {
            $table->id();
            $table->string('el_pastas')->unique();
            $table->string('slaptazodis');
            $table->string('vardas');
            $table->string('pavarde');
            $table->enum('role', ['administratorius', 'darbuotojas', 'klientas'])
                ->default('klientas');
            $table->string('pakvietimo_kodas')->unique()->nullable();
            $table->integer('lojalumo_metai')->nullable();
            $table->boolean('uzblokuotas')->default(false);
            $table->date('paskutinio_incidento_data')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('vartotojas');
    }
};
