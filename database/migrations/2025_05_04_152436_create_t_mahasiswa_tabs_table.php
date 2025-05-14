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
        Schema::create('t_mahasiswa_tabs', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('t_periode_tabs');
            $table->string('name');
            $table->string('nim');
            $table->string('prodi');
            $table->tinyInteger('status_bimbingan_proposal')->default(0)->comment('0 = belum bayar');
            $table->tinyInteger('status_sidang_proposal')->default(0)->comment('0 = belum bayar');
            $table->tinyInteger('status_bimbingan_skripsi')->default(0)->comment('0 = belum bayar');
            $table->tinyInteger('status_sidang_skripsi')->default(0)->comment('0 = belum bayar');
            $table->unsignedInteger('m_status_tabs_id');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('t_mahasiswa_tabs');
    }
};
