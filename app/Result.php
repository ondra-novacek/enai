<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Result extends Model
{
    protected $table = 'survey_results';

    public function person(){
        return $this->belongsTo('App\Person');
    }

    public function version(){
        return $this->hasOne('App\Version');
    }

    public function subresult(){
        return $this->hasMany('App\Subresult');
    }
}
