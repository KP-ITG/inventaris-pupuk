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
        Schema::create('permintaan_distribusi', function (Blueprint $table) {
            $table->id();
            $table->foreignId('kepala_desa_id')->constrained('pengguna')->onDelete('cascade');
            $table->foreignId('desa_id')->constrained('desa')->onDelete('cascade');
            $table->json('items'); // [{pupuk_id, kategori_id, jumlah_diminta}]
            $table->text('keterangan'); // alasan/justifikasi permintaan
            $table->enum('status', ['pending', 'approved', 'rejected'])->default('pending');
            $table->text('alasan_penolakan')->nullable();
            $table->foreignId('approved_by')->nullable()->constrained('pengguna')->onDelete('set null');
            $table->timestamp('approved_at')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('permintaan_distribusi');
    }
};
