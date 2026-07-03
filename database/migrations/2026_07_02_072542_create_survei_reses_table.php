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
        Schema::create('survei_reses', function (Blueprint $table) {
            $table->id();
            $table->foreignId('id_dewan')->constrained('dewans')->cascadeOnDelete();
            $table->foreignId('id_kecamatan')->constrained('kecamatans')->cascadeOnDelete();
            $table->text('alamat')->nullable();
            $table->text('keluhan');
            $table->text('permintaan')->nullable();
            $table->string('foto')->nullable();
            $table->date('tanggal_reses');
            $table->string('status')->default('Baru');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('survei_reses');
    }
};
