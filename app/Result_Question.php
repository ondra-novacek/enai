<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Result_Question extends Model
{
    //disable automatic underscore in laravel
    protected $table = 'survey_result_questions';

    // public function name(){
    //     return $this->hasMany('App\Option');
    // }

    public function option(){
        return $this->belongsTo('App\Option');
    }

    public function person(){
        return $this->belongsTo('App\Person');
    }

    public function question(){
        return $this->belongsTo('App\Question');
    }
}
