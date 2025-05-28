<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('barang', function (Blueprint $table) {
            $table->string('id_barang')->primary();
            $table->string('nama_barang');
            $table->string('status_barang');
            $table->string('jenis_barang');
            $table->string('kondisi');
            $table->text('qr_code')->nullable();
            $table->text('foto_barang')->nullable();
            $table->integer('harga_barang');
            $table->text('deskripsi_barang');
            $table->date('tgl_pembelian');
            $table->foreignId('id_kategori')->constrained('kategori_barang', 'id_kategori');
            $table->timestamps();
        });


    }

    public function down(): void
    {
        Schema::dropIfExists('barang');
    }
};
