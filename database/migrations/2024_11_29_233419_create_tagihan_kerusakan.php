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
        Schema::create('tagihan_kerusakan', function (Blueprint $table) {
            $table->id();
            $table->foreignId('id_laporan_kerusakan')->constrained('laporan_kerusakan', 'id');
            $table->integer('total_tagihan');
            $table->text('bukti_pembayaran')->nullable();
            $table->enum('status', ['pending', 'paid', 'rejected'])->default('pending');
            $table->text('keterangan')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tagihan_kerusakan');
    }
};
