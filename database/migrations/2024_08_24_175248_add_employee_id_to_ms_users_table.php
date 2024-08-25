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
        Schema::table('ms_users', function (Blueprint $table) {
            $table->foreignId('employee_id')->constrained('ms_employees');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('ms_users', function (Blueprint $table) {
            $table->dropColumn('employee_id');
        });
    }
};
