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
        // Many-to-many relationship between expenses and categories
        Schema::create('category_expense', function (Blueprint $table) {
            $table->foreignId('expense_id')->references('id')->on('expenses');
            $table->foreignId('category_id')->references('id')->on('categories');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('category_expense');
    }
};
