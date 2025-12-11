<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('pasiulymas', function (Blueprint $table) {
            $table->unsignedBigInteger('paketas_id')->nullable()->after('vartotojas_id');
            $table->foreign('paketas_id')->references('id')->on('paketas')->onDelete('set null');

            $table->unsignedBigInteger('prasymas_id')->nullable()->after('paketas_id');
            $table->foreign('prasymas_id')->references('id')->on('prasymas')->onDelete('set null');
        });
    }

    public function down()
    {
        Schema::table('pasiulymas', function (Blueprint $table) {
            $table->dropForeign(['prasymas_id']);
            $table->dropColumn('prasymas_id');

            $table->dropForeign(['paketas_id']);
            $table->dropColumn('paketas_id');
        });
    }
};
