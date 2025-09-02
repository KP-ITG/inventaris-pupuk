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
        Schema::create('stok', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('pengguna_id');
            $table->unsignedBigInteger('pupuk_id');
            $table->integer('jumlah_stok')->default(0);
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
        Schema::dropIfExists('stok');
    }
};