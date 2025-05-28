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
        Schema::create('detail_pengajuan_peminjaman', function (Blueprint $table) {
            $table->id();
            $table->uuid('id_pengajuan');
            $table->foreign('id_pengajuan')->references('id_pengajuan')->on('pengajuan_peminjaman');
            $table->string('id_barang');
            $table->foreign('id_barang')->references('id_barang')->on('barang');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('detail_pengajuan_peminjaman');
    }
};
