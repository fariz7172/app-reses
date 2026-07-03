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
        Schema::create('surat_permohonans', function (Blueprint $table) {
            $table->id();
            $table->date('tanggal')->nullable();
            $table->string('nomor_surat')->nullable();
            $table->string('dari')->nullable(); // CAMAT, LURAH, KETUA RT/RW
            $table->foreignId('id_kelurahan')->nullable()->constrained('kelurahans')->nullOnDelete();
            $table->string('lokasi')->nullable();
            $table->string('detail_pemohon')->nullable();
            $table->text('deskripsi')->nullable();
            $table->text('hasil_survei')->nullable();
            $table->string('photo')->nullable();
            $table->string('status')->default('Diterima');
            $table->text('catatan')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('surat_permohonans');
    }
};
