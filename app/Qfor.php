<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Qfor extends Model
{
    protected $table = 'survey_qfors';

    public function question(){
        return $this->belongsToMany('App\Question', 'qfor_question', 'qfor_id', 'question_id');
    }

    public function person(){
        return $this->hasMany('App\Person');
    }
}
