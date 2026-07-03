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
            $table->foreignId('id_pekerjaan_sda')->nullable()->after('id')->constrained('pekerjaan_sdas')->nullOnDelete();
            $table->foreignId('id_kecamatan')->nullable()->after('id_kelurahan')->constrained('kecamatans')->nullOnDelete();
            $table->text('photo')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('surat_permohonans', function (Blueprint $table) {
            $table->dropForeign(['id_pekerjaan_sda']);
            $table->dropColumn('id_pekerjaan_sda');
            $table->dropForeign(['id_kecamatan']);
            $table->dropColumn('id_kecamatan');
            $table->string('photo')->nullable()->change();
        });
    }
};
