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
        Schema::create('factory_account_entries', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('entry_id');
            $table->string('entry_type');
            $table->bigInteger('account_book_id');
            $table->bigInteger('purchase_id')->nullable();
            $table->integer('count')->default(0);
            $table->double('purchase_price')->nullable();
            $table->double('retail_price')->nullable();
            $table->string('description')->nullable();
            $table->bigInteger('account_id')->nullable();
            $table->string('account_name')->nullable();
            $table->double('total_amount')->default(0);
            $table->string('status')->default(1);
            $table->string('closing_id')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('factory_account_entries');
    }
};
