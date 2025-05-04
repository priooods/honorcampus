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
        Schema::create('t_honor_tabs', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('t_mahasiswa_tabs');
            $table->unsignedBigInteger('penguji_satu')->nullable();
            $table->unsignedBigInteger('penguji_dua')->nullable();
            $table->unsignedBigInteger('penguji_tiga')->nullable();
            $table->bigInteger('honor')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('t_honor_tabs');
    }
};
