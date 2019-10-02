<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Question_column extends Model
{
    protected $table = 'survey_question_columns';

    public function question(){
        return $this->belongsTo('App\Question');
    }
}
