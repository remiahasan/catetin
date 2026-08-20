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
        Schema::create('riwayat_stoks', function (Blueprint $table) {
            $table->id();
            $table->foreignId('stock_id')->constrained('stock')->onDelete('cascade');
            $table->enum('status', ['masuk', 'keluar']);
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->integer('jumlah');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('riwayat_stoks');
    }
};
