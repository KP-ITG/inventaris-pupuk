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
        Schema::table('distribusi_pupuk', function (Blueprint $table) {
            $table->foreignId('permintaan_distribusi_id')
                ->nullable()
                ->after('id')
                ->constrained('permintaan_distribusi')
                ->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('distribusi_pupuk', function (Blueprint $table) {
            $table->dropForeign(['permintaan_distribusi_id']);
            $table->dropColumn('permintaan_distribusi_id');
        });
    }
};
