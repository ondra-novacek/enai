<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddQforIdColumnToSurveyTestScoresTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('survey_test_scores', function (Blueprint $table) {
            $table->unsignedInteger('qfor_id');
            // might throw some error
            // $table->foreign('qfor_id')->references('id')->on('survey_qfors');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('survey_test_scores', function (Blueprint $table) {
            $table->dropForeign('survey_test_scores_qfor_id_foreign');

            $table->dropColumn('qfor_id');
        });
    }
}
