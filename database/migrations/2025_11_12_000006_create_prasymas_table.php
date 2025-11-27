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
        Schema::create('prasymas', function (Blueprint $table) {
            $table->id();
            $table->date('data');
            $table->enum('bukle', ['laukiamas', 'issiustas']);
            $table->unsignedBigInteger('vartotojas_id');
            $table->foreign('vartotojas_id')->references('id')->on('vartotojas');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('prasymas');
    }
};
