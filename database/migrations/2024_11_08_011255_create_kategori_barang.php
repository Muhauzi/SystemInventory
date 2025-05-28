<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('kategori_barang', function (Blueprint $table) {
            $table->id('id_kategori');
            $table->string('kode_kategori')->unique();
            $table->string('nama_kategori')->unique();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('kategori_barang');
    }
};
