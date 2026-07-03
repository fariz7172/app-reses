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
        Schema::table('survei_reses', function (Blueprint $table) {
            $table->foreignId('id_kelurahan')->nullable()->after('id_kecamatan')->constrained('kelurahans')->nullOnDelete();
            $table->decimal('lebar', 10, 2)->nullable()->after('foto');
            $table->decimal('tinggi', 10, 2)->nullable()->after('lebar');
            $table->decimal('panjang', 10, 2)->nullable()->after('tinggi');
            $table->decimal('volume', 10, 2)->nullable()->after('panjang');
            $table->bigInteger('estimasi_biaya')->nullable()->after('volume');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('survei_reses', function (Blueprint $table) {
            $table->dropForeign(['id_kelurahan']);
            $table->dropColumn(['id_kelurahan', 'lebar', 'tinggi', 'panjang', 'volume', 'estimasi_biaya']);
        });
    }
};
