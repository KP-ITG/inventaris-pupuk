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
        Schema::create('penggunaan_pupuk', function (Blueprint $table) {
            $table->id();
            $table->foreignId('desa_id')->constrained('desa')->onDelete('cascade');
            $table->foreignId('kepala_desa_id')->constrained('pengguna')->onDelete('cascade');
            $table->foreignId('pupuk_id')->constrained('pupuk')->onDelete('cascade');
            $table->integer('jumlah_digunakan'); // dalam kg
            $table->text('keterangan')->nullable();
            $table->date('tanggal_penggunaan');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('penggunaan_pupuk');
    }
};
