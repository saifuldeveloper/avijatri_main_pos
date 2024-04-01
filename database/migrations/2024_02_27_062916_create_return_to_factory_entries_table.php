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
        Schema::create('return_to_factory_entries', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('return_id');
            $table->bigInteger('account_book_id');
            $table->string('shoe_id');
            $table->integer('count');
            $table->string('status')->default('pending');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('return_to_factory_entries');
    }
};
