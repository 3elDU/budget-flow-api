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
        // Categories which can be assigned to income/expense
        Schema::create('categories', function (Blueprint $table) {
            $table->id();
            $table->tinyText('name');
            $table->string('description', 4096)->nullable();
            $table->tinyText('color_hex');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('categories');
    }
};
