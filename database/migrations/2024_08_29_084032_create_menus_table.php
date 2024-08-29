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
        Schema::create('ms_menus', function (Blueprint $table) {
            $table->id();
            $table->string("name", 100);
            $table->string("url", 100);
            $table->string("icon", 20);
            $table->integer("ord");
            $table->foreignId('parent_id')->nullable()->constrained('ms_menus');
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
        Schema::dropIfExists('ms_menus');
    }
};
