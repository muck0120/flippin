<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCardChoicesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('card_choices', function (Blueprint $table) {
            $table->bigIncrements('card_choice_id');
            $table->unsignedBigInteger('card_id');
            $table->foreign('card_id')->references('card_id')->on('cards')->onDelete('cascade');
            $table->string('card_choice_text', 200);
            $table->boolean('card_choice_is_correct');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('card_choices');
    }
}
