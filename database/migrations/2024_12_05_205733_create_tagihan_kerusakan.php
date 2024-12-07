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
            $table->foreignId('id_laporan_kerusakan')->constrained('laporan_kerusakan', 'id')->onDelete('cascade');
            $table->string('status');
            $table->integer('total_tagihan');
            $table->string('token');
            $table->string('payment_url');
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
