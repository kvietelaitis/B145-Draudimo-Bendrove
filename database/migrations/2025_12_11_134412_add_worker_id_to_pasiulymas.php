<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('pasiulymas', function (Blueprint $table) {
            // Add worker ID (nullable in case system auto-generates offers later)
            $table->unsignedBigInteger('darbuotojas_id')->nullable()->after('vartotojas_id');

            // Add foreign key if your workers are in the 'users' table
            // Adjust 'users' to 'workers' if you have a separate table
            $table->foreign('darbuotojas_id')->references('id')->on('users')->nullOnDelete();
        });
    }

    public function down()
    {
        Schema::table('pasiulymas', function (Blueprint $table) {
            $table->dropForeign(['darbuotojas_id']);
            $table->dropColumn('darbuotojas_id');
        });
    }
};
