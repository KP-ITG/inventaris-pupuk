<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('distribusi_pupuk_detail', function (Blueprint $table) {
            $table->id();
            $table->foreignId('distribusi_pupuk_id')->constrained('distribusi_pupuk')->onDelete('cascade');
            $table->foreignId('pupuk_id')->constrained('pupuk')->onDelete('restrict');
            $table->integer('jumlah_distribusi');
            $table->text('catatan_item')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('distribusi_pupuk_detail');
    }
};
