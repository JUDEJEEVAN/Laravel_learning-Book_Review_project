<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('reviews', function (Blueprint $table) {
            $table->id();
            
            $table->unsignedBigInteger('book_id');
            $table->text('review');
            $table->unsignedTinyInteger('rating');
            $table->foreign('book_id')->references('id')->on('books')->onDelete('cascade');
            // the onDelete('cascade'); method will delete all related data to the book when the book is deleted 
            // this happens at database level.

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('reviews');
    }
};