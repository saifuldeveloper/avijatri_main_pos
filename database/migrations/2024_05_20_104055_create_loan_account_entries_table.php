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
        Schema::create('loan_account_entries', function (Blueprint $table) {
            $table->id();
            $table->integer('entry_id');
            $table->integer('entry_type');
            $table->bigInteger('account_book_id');
            $table->string('account_name');
            $table->bigInteger('account_id');
            $table->string('account_type');
            $table->string('description');
            $table->double('total_amount');
            $table->string('type');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('loan_account_entries');
    }
};
