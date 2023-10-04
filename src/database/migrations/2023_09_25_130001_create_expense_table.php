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
        Schema::create('expenses', function (Blueprint $table) {
            $table->id();
            $table->float('amount');
            $table->foreignId('budget_id')->references('id')->on('budgets');
            $table->foreignId('user_id')->references('id')->on('users');
            $table->timestamps();
            $table->tinyText('name')->nullable();
            $table->string('description', 4096)->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('expenses');
    }
};
