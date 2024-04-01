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
        Schema::create('account_books', function (Blueprint $table) {
            $table->id();
            $table->integer('account_id');
            $table->string('account_type');
            $table->double('previous_balance')->default(0);
            $table->boolean('open')->default(true);
            $table->double('commission')->default(0);
            $table->double('staff')->default(0);
            $table->double('discount')->default(0);
            $table->double('due')->default(0);
            $table->date('deadline')->nullable();
            $table->tinyInteger('balance_carry_forward')->nullable();
            $table->double('closing_balance')->default(0);
            $table->date('closing_date')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('account_books');
    }
};
