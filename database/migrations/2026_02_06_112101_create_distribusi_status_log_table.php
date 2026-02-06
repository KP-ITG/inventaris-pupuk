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
        Schema::create('distribusi_status_log', function (Blueprint $table) {
            $table->id();
            $table->foreignId('distribusi_pupuk_id')->constrained('distribusi_pupuk')->onDelete('cascade');
            $table->string('status_lama')->nullable();
            $table->string('status_baru');
            $table->foreignId('user_id')->nullable()->constrained('pengguna')->onDelete('set null');
            $table->text('keterangan')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('distribusi_status_log');
    }
};
