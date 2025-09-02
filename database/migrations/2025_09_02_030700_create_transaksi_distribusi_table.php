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
        Schema::create('transaksi_distribusi', function (Blueprint $table) {
            $table->id();
            $table->string('kode_transaksi', 50)->unique();
            $table->unsignedBigInteger('pengguna_id');
            $table->unsignedBigInteger('pupuk_id');
            $table->integer('jumlah');
            $table->timestamp('tanggal_transaksi')->useCurrent();
            $table->decimal('harga_saat_transaksi', 12, 2)->nullable();
            $table->string('status', 50);
            $table->timestamps();

            $table->foreign('pengguna_id')->references('id')->on('pengguna')->onDelete('cascade');
            $table->foreign('pupuk_id')->references('id')->on('pupuk')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transaksi_distribusi');
    }
};