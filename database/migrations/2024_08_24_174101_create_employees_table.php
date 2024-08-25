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
        Schema::create('ms_employees', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('jasnita_number');
            $table->string('gender', 10);
            $table->date('dob');
            $table->foreignId('status_id')->constrained('ms_status');
            $table->foreignId('religion_id')->constrained('ms_religions');
            $table->string('address');
            $table->foreignId('created_by')->nullable()->constrained('ms_users');
            $table->foreignId('updated_by')->nullable()->constrained('ms_users');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ms_employees');
    }
};
