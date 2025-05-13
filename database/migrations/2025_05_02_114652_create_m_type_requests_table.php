<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('m_type_requests', function (Blueprint $table) {
            $table->integerIncrements('id');
            $table->string('title');
        });

        DB::table('m_type_requests')->insert(
            array(
                ['title' => 'Proposal'],
                ['title' => 'Skripsi'],
                ['title' => 'Bimbingan'],
                ['title' => 'Sidang'],
            )
        );
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('m_type_requests');
    }
};
