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
            $table->text('lokasi')->nullable()->change();
            $table->text('detail_pemohon')->nullable()->change();
            $table->text('dari')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('surat_permohonans', function (Blueprint $table) {
            $table->string('lokasi', 255)->nullable()->change();
            $table->string('detail_pemohon', 255)->nullable()->change();
            $table->string('dari', 255)->nullable()->change();
        });
    }
};
