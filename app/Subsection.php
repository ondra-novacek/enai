<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Subsection extends Model
{
    protected $table = 'survey_subsections';

    public function question(){
        return $this->hasMany('App\Question');
    }

    public function subresult(){
        return $this->hasMany('App\Subresult');
    }

    public function section_score(){
        return $this->hasMany('App\section_score');
    }
}
