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
        Schema::create('pupuk', function (Blueprint $table) {
            $table->id();
            $table->string('nama_pupuk', 255);
            $table->string('kode_produk', 50)->unique()->nullable();
            $table->text('deskripsi')->nullable();
            $table->decimal('berat_kemasan_kg', 8, 2)->nullable();
            $table->date('tanggal_produksi')->nullable();
            $table->date('tanggal_kadaluarsa')->nullable();
            $table->decimal('harga_beli', 12, 2)->nullable();
            $table->decimal('harga_jual', 12, 2)->nullable();
            $table->integer('stok_gudang_pusat')->default(0);
            $table->unsignedBigInteger('kategori_id')->nullable();
            $table->unsignedBigInteger('supplier_id')->nullable();
            $table->timestamps();

            $table->foreign('kategori_id')->references('id')->on('kategori_pupuk')->onDelete('set null');
            $table->foreign('supplier_id')->references('id')->on('supplier')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pupuk');
    }
};