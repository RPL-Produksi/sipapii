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
        Schema::table('jurnals', function (Blueprint $table) {
            $table->longText('deskripsi_jurnal')->nullable()->change();
            $table->enum('validasi', ['Belum Divalidasi', 'Divalidasi', 'Ditolak', 'Tidak Mengisi'])->default('Belum Divalidasi')->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('jurnals', function (Blueprint $table) {
            $table->longText('deskripsi_jurnal')->change();
            $table->enum('validasi', ['Belum Divalidasi', 'Divalidasi', 'Ditolak'])->default('Belum Divalidasi')->change();
        });
    }
};
