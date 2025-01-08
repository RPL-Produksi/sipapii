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
            $table->dropColumn('foto');

            $table->string('foto_masuk')->nullable()->after('alasan');
            $table->string('foto_pulang')->nullable()->after('foto_masuk');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('absens', function (Blueprint $table) {
            $table->string('foto')->nullable()->after('alasan');

            $table->dropColumn('foto_masuk');
            $table->dropColumn('foto_pulang');
        });
    }
};
