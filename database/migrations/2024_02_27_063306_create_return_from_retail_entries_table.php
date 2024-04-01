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
        Schema::create('return_from_retail_entries', function (Blueprint $table) {
            $table->id();
            $table->integer('account_book_id');
            $table->string('shoe_id');
            $table->integer('count');
            $table->double('commission');
            $table->string('status')->default('pending');
            $table->integer('invoice_id')->default(0);
            $table->integer('extra_shoe')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('return_from_retail_entries');
    }
};
