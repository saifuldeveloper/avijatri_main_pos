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
        Schema::create('expense_account_entries', function (Blueprint $table) {
            $table->id();
            $table->string('entry_id');
            $table->integer('entry_type')->default(0);
            $table->bigInteger('account_book_id');
            $table->string('account_name')->nullable();
            $table->bigInteger('account_id');
            $table->string('account_type');
            $table->string('description')->nullable();
            $table->double('total_amount');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('expense_account_entries');
    }
};
