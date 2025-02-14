<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void {
        Schema::create('expenses', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('userID');
            $table->string('subject');
            $table->decimal('price', 10, 2);
            $table->boolean('paid');
            $table->date('date');
            $table->enum('category',['Others', 'Eatables', 'Leisure', 'Electronics', 'Utilities', 'Clothes', 'Health']);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('expenses');
    }
};
