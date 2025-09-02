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
        Schema::create('pupuk_nutrisi', function (Blueprint $table) {
            $table->unsignedBigInteger('pupuk_id');
            $table->unsignedBigInteger('nutrisi_id');
            $table->decimal('kandungan_persen', 5, 2)->nullable();
            $table->timestamps();

            $table->primary(['pupuk_id', 'nutrisi_id']);
            $table->foreign('pupuk_id')->references('id')->on('pupuk')->onDelete('cascade');
            $table->foreign('nutrisi_id')->references('id')->on('nutrisi')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pupuk_nutrisi');
    }
};