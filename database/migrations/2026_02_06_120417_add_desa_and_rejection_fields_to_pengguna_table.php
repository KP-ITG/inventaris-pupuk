<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('pengguna', function (Blueprint $table) {
            // Update role enum untuk menambah kepala_desa
            DB::statement("ALTER TABLE pengguna MODIFY COLUMN role ENUM('admin', 'distributor', 'kepala_desa') DEFAULT 'distributor'");

            // Tambah field desa_id untuk kepala desa
            $table->foreignId('desa_id')->nullable()->after('role')->constrained('desa')->onDelete('set null');

            // Tambah field alasan penolakan
            $table->text('alasan_penolakan')->nullable()->after('status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('pengguna', function (Blueprint $table) {
            $table->dropForeign(['desa_id']);
            $table->dropColumn(['desa_id', 'alasan_penolakan']);

            // Kembalikan role enum ke semula
            DB::statement("ALTER TABLE pengguna MODIFY COLUMN role ENUM('admin', 'distributor') DEFAULT 'distributor'");
        });
    }
};
