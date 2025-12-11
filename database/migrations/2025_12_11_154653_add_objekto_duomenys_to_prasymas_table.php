<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::table('prasymas', function (Blueprint $table) {
            $table->json('objekto_duomenys')->nullable()->after('paketas_id');
        });
    }

    public function down()
    {
        Schema::table('prasymas', function (Blueprint $table) {
            $table->dropColumn('objekto_duomenys');
        });
    }
};
