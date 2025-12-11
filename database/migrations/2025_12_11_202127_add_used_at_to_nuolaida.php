<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('nuolaida', function (Blueprint $table) {
            $table->timestamp('panaudojimo_laikas')->nullable()->after('galiojimo_pabaiga');
        });
    }

    public function down()
    {
        Schema::table('nuolaida', function (Blueprint $table) {
            $table->dropColumn('panaudojimo_laikas');
        });
    }
};
