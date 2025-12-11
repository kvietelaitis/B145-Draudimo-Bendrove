<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('pasiulymas', function (Blueprint $table) {
            $table->json('kainos_detales')->nullable()->after('koreguota_kaina');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('pasiulymas', function (Blueprint $table) {
            //
        });
    }
};
