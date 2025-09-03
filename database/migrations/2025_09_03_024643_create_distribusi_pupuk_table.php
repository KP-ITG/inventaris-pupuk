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
        Schema::create('distribusi_pupuk', function (Blueprint $table) {
            $table->id();
            $table->string('nomor_distribusi')->unique(); // nomor surat distribusi
            $table->foreignId('pupuk_id')->constrained('pupuk')->onDelete('cascade');
            $table->foreignId('desa_id')->constrained('desa')->onDelete('cascade');
            $table->foreignId('pengguna_id')->constrained('pengguna')->onDelete('cascade'); // petugas yang input
            $table->integer('jumlah_distribusi'); // jumlah yang didistribusikan
            $table->date('tanggal_distribusi');
            $table->date('tanggal_realisasi')->nullable(); // tanggal pupuk sampai di desa
            $table->enum('status_distribusi', ['rencana', 'dalam_perjalanan', 'selesai', 'batal'])->default('rencana');
            $table->text('catatan')->nullable();
            $table->string('nama_penerima')->nullable(); // nama yang menerima di desa
            $table->string('jabatan_penerima')->nullable(); // jabatan penerima (kepala desa, BPD, dll)
            $table->string('no_telepon_penerima', 20)->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('distribusi_pupuk');
    }
};
