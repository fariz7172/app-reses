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
        Schema::table('pekerjaan_sdas', function (Blueprint $table) {
            $table->unsignedBigInteger('id_pelaksana')->nullable();
            $table->unsignedBigInteger('id_vendor')->nullable();

            $table->foreign('id_pelaksana')->references('id')->on('pelaksanas')->onDelete('set null');
            $table->foreign('id_vendor')->references('id')->on('vendors')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('pekerjaan_sdas', function (Blueprint $table) {
            $table->dropForeign(['id_pelaksana']);
            $table->dropForeign(['id_vendor']);
            $table->dropColumn('id_pelaksana');
            $table->dropColumn('id_vendor');
        });
    }
};
