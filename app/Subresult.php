<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Subresult extends Model
{
    protected $table = 'survey_subresults';

    public function result(){
        return $this->belongsTo('App\Result');
    }

    public function subsection(){
        return $this->belongsTo('App\Subsection');
    }
}
