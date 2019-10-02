<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Person extends Model
{
    protected $table = 'survey_people';
    
    // public function result(){
    //     return $this->hasOne('App\Result');
    // }

    public function result_question(){
        return $this->hasOne('App\Result_Question');
    }

    public function qfor(){
        return $this->belongsTo('App\Qfor', 'q_for_id', 'id' );
    }
}
