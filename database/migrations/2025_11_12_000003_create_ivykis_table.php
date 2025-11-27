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
        Schema::create('ivykis', function (Blueprint $table) {
            $table->id();
            $table->date('ivykio_data');
            $table->date('pranesimo_data');
            $table->enum('bukle', ['ismoketa', 'atmesta', 'tiriamas', 'pateiktas', 'patvirtintas']);
            $table->string('apibudinimas');
            $table->unsignedBigInteger('tipas_id');
            $table->unsignedBigInteger('vartotojas_id');
            $table->foreign('tipas_id')->references('id')->on('ivykio_tipas');
            $table->foreign('vartotojas_id')->references('id')->on('vartotojas');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ivykis');
    }
};
