<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('pasiulymas', function (Blueprint $table) {
            // 1. Drop the incorrect constraint pointing to 'users'
            $table->dropForeign(['darbuotojas_id']);

            // 2. Add the correct constraint pointing to 'vartotojas'
            $table->foreign('darbuotojas_id')
                ->references('id')
                ->on('vartotojas') // <--- This was the missing link
                ->nullOnDelete();
        });
    }

    public function down()
    {
        Schema::table('pasiulymas', function (Blueprint $table) {
            $table->dropForeign(['darbuotojas_id']);

            // Restore the old (incorrect) one if rolling back
            $table->foreign('darbuotojas_id')
                ->references('id')
                ->on('users')
                ->nullOnDelete();
        });
    }
};
