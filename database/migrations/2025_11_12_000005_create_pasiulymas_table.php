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
            $table->enum('bukle', ['ismoketa', 'priimtas', 'atmestas']);
            $table->date('sukurimo_data');
            $table->unsignedBigInteger('vartotojas_id');
            $table->foreign('vartotojas_id')->references('id')->on('vartotojas');
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
