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
        Schema::create('cheque_account_entries', function (Blueprint $table) {
            $table->id();
            $table->string('entry_id');
            $table->integer('entry_type')->default(0);
            $table->integer('account_book_id')->default(0);
            $table->double('total_amount');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cheque_account_entries');
    }
};
