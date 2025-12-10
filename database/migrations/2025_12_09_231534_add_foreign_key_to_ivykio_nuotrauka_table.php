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
        Schema::table('ivykio_nuotrauka', function (Blueprint $table) {
            $table->foreignId('ivykis_id')
                ->constrained('ivykis')
                ->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::table('ivykio_nuotrauka', function (Blueprint $table) {
            $table->dropForeign(['ivykis_id']);
        });
    }
};
