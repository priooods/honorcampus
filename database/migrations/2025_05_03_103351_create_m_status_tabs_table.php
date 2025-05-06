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
        Schema::create('m_status_tabs', function (Blueprint $table) {
            $table->integerIncrements('id');
            $table->string('title');
        });

        DB::table('m_status_tabs')->insert(
            array(
                ['title' => 'Aktif'],
                ['title' => 'Tidak Aktif'],
                ['title' => 'Disetujui'],
                ['title' => 'Tidak Disetujui'],
                ['title' => 'Pengajuan'],
                ['title' => 'Batal Pengajuan'],
                ['title' => 'Draft'],
            )
        );
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('m_status_tabs');
    }
};
