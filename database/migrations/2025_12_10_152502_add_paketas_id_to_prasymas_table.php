<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddPaketasIdToPrasymasTable extends Migration
{
    public function up()
    {
        Schema::table('prasymas', function (Blueprint $table) {
            $table->unsignedBigInteger('paketas_id')->nullable()->after('vartotojas_id');
            $table->foreign('paketas_id')->references('id')->on('paketas')->onDelete('set null');
        });
    }

    public function down()
    {
        Schema::table('prasymas', function (Blueprint $table) {
            $table->dropForeign(['paketas_id']);
            $table->dropColumn('paketas_id');
        });
    }
}
