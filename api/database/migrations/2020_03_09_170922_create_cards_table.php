<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCardsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cards', function (Blueprint $table) {
            $table->bigIncrements('card_id');
            $table->unsignedBigInteger('book_id');
            $table->foreign('book_id')->references('book_id')->on('books')->onDelete('cascade');
            $table->unsignedBigInteger('card_order');
            $table->string('card_question', 2000);
            $table->string('card_question_image', 200)->nullable();
            $table->string('card_explanation', 2000)->nullable();
            $table->string('card_explanation_image', 200)->nullable();
            $table->dateTime('card_created_at');
            $table->dateTime('card_updated_at');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('cards');
    }
}
