<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Question extends Model
{
    protected $table = 'survey_questions';

    public function option(){
        return $this->hasMany('App\Option');
    }

    public function question_column(){
        return $this->hasMany('App\Question_column', 'question_id', 'question_id', ''); //column name in question_column, column name in question, '' void 
    }

    public function qtype(){
        return $this->belongsTo('App\Qtype');
    }

    public function subsection(){
        return $this->belongsTo('App\Subsection');
    }

    public function qfor(){
        return $this->belongsToMany('App\Qfor', 'survey_qfor_question', 'question_id', 'qfor_id');
    }

    public function result_question(){
        return $this->hasMany('App\Result_Question');
    }

}
