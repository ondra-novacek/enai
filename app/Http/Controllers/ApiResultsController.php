<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Result_Question;
use App\Person;

use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;

class ApiResultsController extends Controller
{

    public function getAllResults(){
        $results = Result_Question::with('option')
                    ->with('question')
                    ->with('person.qfor')
                    ->take(50)
                    ->get();
        return $results;
    }

    public function getAllPeople(){
        $people = DB::table("survey_people")
        ->join('survey_qfors', 'survey_people.q_for_id', '=', 'survey_qfors.id')
        ->join('survey_results', 'survey_people.id', '=', 'survey_results.person_id') //id will be results.id and timestamp will be results.timestamp (cause its last join)
        ->select('*')
        ->get();

        return $people;
    }
}
