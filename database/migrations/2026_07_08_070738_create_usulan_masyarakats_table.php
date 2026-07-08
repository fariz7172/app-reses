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
        Schema::create('usulan_masyarakats', function (Blueprint $table) {
            $table->id();
            $table->string('nama_pengusul');
            $table->foreignId('id_kecamatan')->nullable()->constrained('kecamatans')->nullOnDelete();
            $table->foreignId('id_kelurahan')->nullable()->constrained('kelurahans')->nullOnDelete();
            $table->text('alamat')->nullable();
            $table->text('deskripsi_usulan')->nullable();
            $table->string('latitude')->nullable();
            $table->string('longitude')->nullable();
            $table->json('photo')->nullable();
            $table->string('status')->default('Menunggu'); // Menunggu, Diproses, Ditolak, Selesai
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('usulan_masyarakats');
    }
};
