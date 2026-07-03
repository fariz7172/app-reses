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
        Schema::create('pekerjaan_sdas', function (Blueprint $table) {
            $table->id();
            $table->string('no_skpd')->nullable();
            $table->integer('tahun_monev')->nullable();
            $table->string('sumber_data')->nullable();
            $table->date('tgl_input')->nullable();
            $table->string('rincian_sumber_data')->nullable();
            $table->string('kode_tracking')->unique()->nullable();
            $table->foreignId('id_dewan')->nullable()->constrained('dewans')->nullOnDelete();
            $table->foreignId('id_kelurahan')->nullable()->constrained('kelurahans')->nullOnDelete();
            $table->string('rt')->nullable();
            $table->string('rw')->nullable();
            $table->text('alamat')->nullable();
            $table->text('deskripsi')->nullable();
            $table->string('lingkup_kewenangan')->nullable();
            $table->string('kategori_pekerjaan')->nullable();
            $table->string('kategori_prioritas')->nullable();
            $table->string('status_tindak_lanjut')->nullable();
            $table->date('tgl_survei')->nullable();
            $table->string('volume_panjang')->nullable();
            $table->integer('tahun_dikerjakan')->nullable();
            $table->string('metode_pekerjaan')->nullable();
            $table->string('checklist_perencanaan')->nullable();
            $table->date('estimasi_tgl_realisasi')->nullable();
            $table->date('tgl_mulai')->nullable();
            $table->date('tgl_selesai')->nullable();
            $table->integer('progress')->default(0);
            $table->string('photo')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pekerjaan_sdas');
    }
};
