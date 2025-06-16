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
        Schema::table('absens', function (Blueprint $table) {
            $table->unsignedTinyInteger('rating_tugas')->nullable()->after('foto_pulang');
            $table->unsignedTinyInteger('rating_kompetensi')->nullable()->after('rating_tugas');
        });

        Schema::table('absensis', function (Blueprint $table) {
            $table->unsignedTinyInteger('rating_tugas')->nullable()->after('foto_pulang');
            $table->unsignedTinyInteger('rating_kompetensi')->nullable()->after('rating_tugas');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('absens', function (Blueprint $table) {
            $table->dropColumn('rating_tugas');
            $table->dropColumn('rating_kompetensi');
        });

        Schema::table('absensis', function (Blueprint $table) {
            $table->dropColumn('rating_tugas');
            $table->dropColumn('rating_kompetensi');
        });
    }
};
