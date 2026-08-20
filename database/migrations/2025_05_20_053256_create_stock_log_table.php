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
        Schema::create('stock_log', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('stock_id');
            $table->integer('stok_awal');
            $table->integer('stok_akhir')->nullable();
            $table->integer('stok_keluar')->nullable();
            $table->foreign('stock_id')->references('id')->on('stock')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('stock_log');
    }
};
