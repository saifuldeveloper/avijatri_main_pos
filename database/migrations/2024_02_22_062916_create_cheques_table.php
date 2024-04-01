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
        Schema::create('cheques', function (Blueprint $table) {
            $table->string('id')->nullable();
            $table->integer('account_book_id');
            $table->double('amount');
            $table->date('due_date')->nullable();
            $table->string('attachment_type')->nullable();
            $table->integer('attachment_id')->default(0);
            $table->integer('closing_id')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cheques');
    }
};
