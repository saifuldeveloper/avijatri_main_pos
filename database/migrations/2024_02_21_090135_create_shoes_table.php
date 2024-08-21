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
        Schema::create('shoes', function (Blueprint $table) {
            $table->string('id')->unique();
            $table->integer('factory_id')->default(0);
            $table->integer('category_id');
            $table->integer('color_id');
            $table->double('purchase_price')->default(0);
            $table->double('retail_price');
            $table->integer('box_id');
            $table->integer('bag_id');
            $table->string('image');
            $table->integer('initial_count')->default(0);
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('shoes');
    }
};
