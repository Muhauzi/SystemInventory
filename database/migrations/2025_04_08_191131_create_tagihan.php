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
        Schema::create('tagihan', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignId('id_peminjaman')->constrained('peminjaman', 'id_peminjaman')->onDelete('cascade');
            $table->string('jenis_tagihan');
            $table->string('jumlah_tagihan');
            $table->text('bukti_pembayaran')->nullable();
            $table->string('status_pembayaran')->default('Belum Lunas');
            $table->string('token')->nullable();
            $table->string('payment_url')->nullable();
            $table->string('link_payment_created_at')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tagihan');
    }
};
