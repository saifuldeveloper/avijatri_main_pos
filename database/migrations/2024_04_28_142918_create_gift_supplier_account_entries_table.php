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
        Schema::create('gift_supplier_account_entries', function (Blueprint $table) {
            $table->id();
            $table->string('entry_id');
            $table->integer('entry_type')->default(0);
            $table->bigInteger('account_book_id');
            $table->string('gift_purchase_id')->nullable();
            $table->longText('gift_name')->nullable();
            $table->integer('count')->nullable();
            $table->double('unit_price')->nullable();
            $table->double('total_amount')->nullable();
            $table->string('description')->nullable();
            $table->bigInteger('account_id')->nullable();
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
        Schema::dropIfExists('gift_supplier_account_entries');
    }
};
