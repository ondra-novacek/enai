<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Section_Score extends Model
{
    //disable automatic underscore in laravel
    protected $table = 'survey_section_scores';

    public function subsection(){
        return $this->belongsTo('App\Subsection');
    }
}
