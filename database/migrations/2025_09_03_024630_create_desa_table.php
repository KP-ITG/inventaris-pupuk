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
        Schema::create('desa', function (Blueprint $table) {
            $table->id();
            $table->string('nama_desa');
            $table->string('kecamatan');
            $table->string('kabupaten');
            $table->string('provinsi');
            $table->text('alamat_lengkap')->nullable();
            $table->string('kode_pos', 10)->nullable();
            $table->string('nama_kepala_desa')->nullable();
            $table->string('no_telepon', 20)->nullable();
            $table->integer('jumlah_penduduk')->nullable();
            $table->decimal('luas_wilayah', 10, 2)->nullable(); // dalam hektar
            $table->enum('status', ['aktif', 'non_aktif'])->default('aktif');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('desa');
    }
};
