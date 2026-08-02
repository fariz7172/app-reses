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
        Schema::table('surat_permohonans', function (Blueprint $table) {
            $table->text('photo')->nullable()->change();
        });

        Schema::table('pekerjaan_sdas', function (Blueprint $table) {
            $table->text('photo')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('surat_permohonans', function (Blueprint $table) {
            $table->string('photo', 255)->nullable()->change();
        });

        Schema::table('pekerjaan_sdas', function (Blueprint $table) {
            $table->string('photo', 255)->nullable()->change();
        });
    }
};
