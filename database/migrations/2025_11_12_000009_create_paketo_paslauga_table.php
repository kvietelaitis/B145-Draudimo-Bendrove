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
        Schema::create('paketo_paslauga', function (Blueprint $table) {
            $table->id();
            $table->string('pavadinimas');
            $table->string('apibudinimas');
            $table->double('kaina');
            $table->unsignedBigInteger('paketas_id');
            $table->foreign('paketas_id')->references('id')->on('paketas');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('paketo_paslauga');
    }
};
