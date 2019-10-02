<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddResultQuestionTableAndDropOldResultsTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::dropIfExists('survey_results');
        Schema::dropIfExists('survey_subresults');

        Schema::create('survey_result_questions', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('question_id');
            $table->unsignedInteger('option_id')->nullable();
            $table->unsignedInteger('person_id');
            $table->unsignedInteger('points')->nullable();
            $table->unsignedInteger('one_to_five_selected')->nullable();

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('survey_result_questions');

        // Schema::table('survey_result_questions', function (Blueprint $table) {
            
        // });
    }
}
