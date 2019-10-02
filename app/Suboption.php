<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Suboption extends Model
{
    //
    protected $table = 'survey_suboptions';

    public function question(){
        return $this->belongsTo('App\Option');
    }
}
