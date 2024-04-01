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
        Schema::create('inventories', function (Blueprint $table) {
            $table->string('id');
            $table->string('factory')->nullable();
            $table->string('category')->nullable();
            $table->string('color')->nullable();
            $table->double('purchase_price')->nullable();
            $table->double('retail_price')->nullable();
            $table->decimal('count')->default(0)->nullable();
            $table->string('image')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('inventories');
    }
};
