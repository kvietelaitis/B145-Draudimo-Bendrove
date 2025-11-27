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
        Schema::create('ivykio_nuotrauka', function (Blueprint $table) {
            $table->id();
            $table->string('failo_vieta');
            $table->string('failo_pavadinimas');
            $table->unsignedBigInteger('ivykis_id');
            $table->foreign('ivykis_id')->references('id')->on('ivykis');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ivykio_nuotrauka');
    }
};
