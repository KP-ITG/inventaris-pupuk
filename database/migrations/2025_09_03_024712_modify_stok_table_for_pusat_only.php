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
        Schema::table('stok', function (Blueprint $table) {
            // Tambah kolumn untuk stok pusat
            $table->integer('stok_minimum')->default(0)->after('jumlah_stok'); // batas minimum stok
            $table->integer('stok_maksimum')->default(0)->after('stok_minimum'); // batas maksimum stok
            $table->text('lokasi_gudang')->nullable()->after('stok_maksimum'); // lokasi penyimpanan
            $table->enum('status_stok', ['aman', 'hampir_habis', 'habis'])->default('aman')->after('lokasi_gudang');
        });
    }

    public function down(): void
    {
        Schema::table('stok', function (Blueprint $table) {
            $table->dropColumn(['stok_minimum', 'stok_maksimum', 'lokasi_gudang', 'status_stok']);
        });
    }
};
