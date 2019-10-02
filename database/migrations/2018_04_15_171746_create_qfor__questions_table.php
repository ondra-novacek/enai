<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateQforQuestionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('survey_qfor_question', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('question_id')->unsigned();
            // not necessary when using laravel convention
            $table->foreign('question_id')->references('id')->on('survey_questions');

            $table->foreign('qfor_id')->references('id')->on('survey_qfors');
            $table->integer('qfor_id')->unsigned();
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
        Schema::dropIfExists('survey_qfor_question');
    }
}
