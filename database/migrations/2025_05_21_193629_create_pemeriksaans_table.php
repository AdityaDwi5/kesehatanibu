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
       Schema::create('pemeriksaans', function (Blueprint $table) {
            $table->id();
            $table->foreignId('ibu_id')->constrained('ibus')->onDelete('cascade');
            $table->date('tanggal_pemeriksaan');
            $table->integer('usia_kehamilan');
            $table->integer('tekanan_darah');
            $table->string('riwayat_penyakit'); // yes/no
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pemeriksaans');
    }
};
