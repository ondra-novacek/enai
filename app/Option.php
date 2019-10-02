<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Option extends Model
{
    // laravel automaticaly asumes the name of a table, so if you follow convention, you dont have to write this (meaning if zour table name is model name + 's' (options))
    protected $table = 'survey_options';

    public function question(){
        return $this->belongsTo('App\Question');
    }

    public function result_question(){
        return $this->hasMany('App\Result_Question');
    }

    public function suboption(){
        return $this->hasMany('App\Suboption');
    }
}
