<?php

namespace App\Http\Controllers;

use App\Exports\ResultsExport;
use Excel;
use App\Result_Question;

// use Maatwebsite\Excel\Facades\Excel;
// use App\Http\Controllers\Controller;

class ExportDataController extends Controller
{
    public function __construct()
    {
        #you have to be logged in to all routes(that goes through this controller) in order to see them
        $this->middleware('auth');
    }

    public function export() 
    {
        $results = Result_Question::join('survey_questions', 'question_id', '=', 'survey_questions.id')
                    ->join('survey_options', 'option_id', 'survey_options.id')  
                    ->join('survey_people', 'person_id', 'survey_people.id')
                    ->join('survey_qfors', 'survey_people.q_for_id', 'survey_qfors.id')
                    ->select('survey_people.id as id', 'survey_people.age as age', 'survey_people.gender as gender', 'survey_qfors.name as profession', 
                             'survey_people.country as country', 'survey_questions.text as question', 'survey_options.name as option', 'one_to_five_selected as onetofiveSelected', 'points', 'survey_result_questions.created_at as submitted')
                    //->select('survey_result_questions.created_at','points', 'survey_people.age')
                    ->get();
                    
        $results_array[] = array('id','age','gender','profession','country','question','option','onetofiveSelected','points','submitted');
        foreach($results as $result){
          $results_array[] = array(
            'id' => $result->id,
            'age' => $result->age,
            'gender' => $result->gender,
            'profession' => $result->profession,
            'country' => $result->country,
            'question' => $result->question,
            'option' => $result->option,
            'onetofiveSelected' => $result->onetofiveSelected,
            'points' => $result->points,
            'submitted' => $result->submitted    
          );
        }                    

        //return Excel::download($results, 'users.xlsx');  --version 3+
        Excel::create('Result Data', function($excel) use ($results_array){
          $excel->setTitle('Result Data');
          $excel->sheet('Result Data', function($sheet) use ($results_array){
           $sheet->fromArray($results_array, null, 'A1', false, false);
          });
        })->download('xlsx');
    }
    
    public function exportCsv() 
    {
        $results = Result_Question::join('survey_questions', 'question_id', '=', 'survey_questions.id')
                    ->join('survey_options', 'option_id', 'survey_options.id')  
                    ->join('survey_people', 'person_id', 'survey_people.id')
                    ->join('survey_qfors', 'survey_people.q_for_id', 'survey_qfors.id')
                    ->select('survey_people.id as id', 'survey_people.age as age', 'survey_people.gender as gender', 'survey_qfors.name as profession', 
                             'survey_people.country as country', 'survey_questions.text as question', 'survey_options.name as option', 'one_to_five_selected as onetofiveSelected', 'points', 'survey_result_questions.created_at as submitted')
                    //->select('survey_result_questions.created_at','points', 'survey_people.age')
                    ->get();
                    
        $results_array[] = array('id','age','gender','profession','country','question','option','onetofiveSelected','points','submitted');
        foreach($results as $result){
          $results_array[] = array(
            'id' => $result->id,
            'age' => $result->age,
            'gender' => $result->gender,
            'profession' => $result->profession,
            'country' => $result->country,
            'question' => $result->question,
            'option' => $result->option,
            'onetofiveSelected' => $result->onetofiveSelected,
            'points' => $result->points,
            'submitted' => $result->submitted    
          );
        }                    

        //return Excel::download($results, 'users.xlsx');  --version 3+
        Excel::create('Result Data', function($excel) use ($results_array){
          $excel->setTitle('Result Data');
          $excel->sheet('Result Data', function($sheet) use ($results_array){
           $sheet->fromArray($results_array, null, 'A1', false, false);
          });
        })->download('csv');
    }
}
