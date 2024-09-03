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
        Schema::create('ms_emergency', function (Blueprint $table) {
            $table->id();
            $table->string('period', 50);
            $table->year('year');
            $table->foreignId('district_id')->nullable()->constrained('ms_regencies');
            $table->integer('kecelakaan');
            $table->integer('kebakaran');
            $table->integer('ambulan_gratis');
            $table->integer('mobil_jenazah');
            $table->integer('penanganan_hewan');
            $table->integer('keamanan');
            $table->integer('kriminal');
            $table->integer('bencana_alam');
            $table->integer('kdrt');
            $table->integer('gawat_darurat_lain');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ms_emergency');
    }
};
