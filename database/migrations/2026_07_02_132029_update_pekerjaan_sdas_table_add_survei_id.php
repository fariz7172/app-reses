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
            $table->foreignId('id_survei_reses')->nullable()->after('id')->constrained('survei_reses')->nullOnDelete();
            $table->foreignId('id_kecamatan')->nullable()->after('id_dewan')->constrained('kecamatans')->nullOnDelete();
            $table->text('photo')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('pekerjaan_sdas', function (Blueprint $table) {
            $table->dropForeign(['id_survei_reses']);
            $table->dropColumn('id_survei_reses');
            $table->dropForeign(['id_kecamatan']);
            $table->dropColumn('id_kecamatan');
            $table->string('photo')->nullable()->change();
        });
    }
};
