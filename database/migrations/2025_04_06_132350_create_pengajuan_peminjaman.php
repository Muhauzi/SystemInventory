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
        Schema::create('pengajuan_peminjaman', function (Blueprint $table) {
            $table->uuid('id_pengajuan')->primary();
            $table->foreignId('id_user')->constrained('users', 'id');
            $table->date('tanggal_pengajuan');
            $table->date('tanggal_mulai');
            $table->date('tanggal_selesai');
            $table->string('status_pengajuan')->default('pending');
            $table->string('alasan')->nullable();
            $table->text('surat_pengantar');
            $table->text('keterangan_pengajuan')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pengajuan_peminjaman');
    }
};
