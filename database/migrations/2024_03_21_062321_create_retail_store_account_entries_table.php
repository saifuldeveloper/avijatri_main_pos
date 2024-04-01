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
        Schema::create('retail_store_account_entries', function (Blueprint $table) {
            $table->id();
            $table->string('entry_id')->nullable();
            $table->bigInteger('entry_type')->nullable();
            $table->bigInteger('account_book_id')->nullable();
            $table->integer('invoice_id')->nullable();
            $table->decimal('count')->nullable();
            $table->double('total_retail_price')->nullable();
            $table->decimal('return_count')->nullable();
            $table->double('return_amount')->nullable();
            $table->double('return_amount_without_commission')->nullable();
            $table->mediumText('expense_description')->nullable();
            $table->double('total_commission')->nullable();
            $table->double('transport')->nullable();
            $table->double('discount')->nullable();
            $table->double('amount')->nullable();
            $table->double('paid_amount')->nullable();
            $table->string('description')->nullable();
            $table->mediumText('account_name')->nullable();
            $table->string('closing_id')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('retail_store_account_entries');
    }
};
