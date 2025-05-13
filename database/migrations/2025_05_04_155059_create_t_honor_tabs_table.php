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
            $table->integer('sequent')->nullable();
            $table->unsignedBigInteger('m_dosen_tabs_id')->nullable();
            $table->unsignedInteger('m_type_request_id')->nullable();
            $table->unsignedInteger('m_type_request_id_detail')->nullable();
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
