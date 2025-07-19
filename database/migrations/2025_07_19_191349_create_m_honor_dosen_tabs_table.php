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
        Schema::create('m_honor_dosen_tabs', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('t_periode_tabs');
            $table->integer('type');
            $table->integer('price');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('m_honor_dosen_tabs');
    }
};
