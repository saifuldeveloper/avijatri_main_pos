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
        Schema::create('inventory_check_details', function (Blueprint $table) {
            $table->integer('serial_no')->nullable()->unsigned();
            $table->string('id')->nullable();
            $table->integer('inventory_check_id')->nullable();
            $table->string('factory')->nullable();
            $table->string('category')->nullable();
            $table->string('color')->nullable();
            $table->double('retail_price')->nullable();
            $table->double('purchase_price')->nullable();
            $table->decimal('count')->nullable();
            $table->text('total_count_breakdown')->nullable();
            $table->decimal('remaining')->nullable();
            $table->string('image')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('inventory_check_details');
    }
};
