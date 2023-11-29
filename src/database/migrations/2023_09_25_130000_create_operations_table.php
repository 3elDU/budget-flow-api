<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('operations', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('amount');
            $table->foreignId('budget_id')->references('id')->on('budgets');
            $table->foreignId('user_id')->references('id')->on('users');
            $table->timestamps();
            $table->tinyText('name')->nullable();
            $table->string('description', 4096)->nullable();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('operations');
    }
};
