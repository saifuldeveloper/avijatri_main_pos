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
        Schema::create('transactions', function (Blueprint $table) {
            $table->id();
            $table->integer('from_account_id');
            $table->integer('to_account_id');
            $table->double('amount');
            $table->string('description')->nullable();
            $table->integer('closing_id')->nullable();
            $table->string('attachment_type')->nullable();
            $table->string('transaction_type')->nullable();
            $table->integer('attachment_id')->default(0);
            $table->string('payment_type')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transactions');
    }
};
