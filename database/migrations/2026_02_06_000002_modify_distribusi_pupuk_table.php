<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('distribusi_pupuk', function (Blueprint $table) {
            // Hapus kolom pupuk_id dan jumlah_distribusi karena pindah ke detail
            $table->dropForeign(['pupuk_id']);
            $table->dropColumn(['pupuk_id', 'jumlah_distribusi']);
        });
    }

    public function down(): void
    {
        Schema::table('distribusi_pupuk', function (Blueprint $table) {
            $table->foreignId('pupuk_id')->nullable()->constrained('pupuk')->onDelete('restrict');
            $table->integer('jumlah_distribusi')->default(0);
        });
    }
};
