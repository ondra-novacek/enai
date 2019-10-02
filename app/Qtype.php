<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Qtype extends Model
{
    protected $table = 'survey_qtypes';

    public function question(){
        return $this->hasMany('App\Question');
    }
}
