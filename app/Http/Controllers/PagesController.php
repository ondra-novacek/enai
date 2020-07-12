<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Question;
use App\Option;
use App\Qfor;
use App\Qfor_question;
use App\Result;

use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;

class PagesController extends Controller
{
    public function index(){
        #get descriptions of surveys
        try{
            $descriptions = $this->getDescriptions();
        }catch(\Exception $e){
            return view('pages.index');
        }
        
        #renders basic starting page, where user chooses between 3 surveys
        return view('pages.index')->with('descriptions', $descriptions);
    }

    public function survey($who){
        $who = $this->determineWHO($who);

        if ($who == 0) { return redirect('/'); }

        try{
            $whoName = $this->who($who);
            $options = $this->options($who);     
            $sections = $this->sections($who);
        }catch(\Exception $e){
            return redirect('/')->with('msg', 'Database is offline. Please return another time.');
        } 
        //demographic questions
        $demoQuestions = $this->demoQuestions($who);
        //intro of the test
        $intro = $this->getIntro($who);
        if (empty($sections)){
            return redirect('/')->with('msg', 'No questions for this survey yet.');
        }

        return view('dev.survey')->with('options', $options)->with('sections', $sections)->with('whoName', $whoName)->with('demoquestions', $demoQuestions)->with('intro', $intro);
    }

    #returns surveys for
    public function surveysfor(){
        $surveys = DB::table('survey_qfors')
                    ->select('*')
                    ->get();
        return $surveys;            
    }

    #you give id of a survey, returns name of the survey
    public function who($who) {
        $whoName = DB::table('survey_qfors')
                        ->select('*')
                        ->where('id', '=', $who)
                        ->get();
        return $whoName;                
    }


    #finds options to every question for e.g. student (1) and stores it as an array 
    public function options($who){
        $questions = DB::table("survey_questions")
                        ->join('survey_qfor_question', 'survey_questions.id', '=', 'survey_qfor_question.question_id')
                        ->select('*')
                        ->where('qfor_id','=',$who)
                        ->get();

        $options = [];
        foreach ($questions as $q) {
            $opts = Option::join('survey_questions', 'survey_questions.id', 'survey_options.question_id')->with('suboption')->where('survey_options.question_id', $q->question_id)
                    ->select('survey_options.id AS id', 'survey_options.name', 'survey_options.question_id', 'survey_options.feedback', 'survey_options.value_not_checked', 'survey_options.feedback_not_checked', 'survey_options.value', 'survey_questions.qtype_id', 'survey_questions.rateTo')
                    ->orderBy('survey_options.created_at')
                    ->get();
            $options[$q->question_id] = $opts;
        }
        return $options;
    }

    #divides questions into sections, returns as an array, argument is for what survey it is
    public function sections($who){

        $sections = [];
        $subsections = DB::table('survey_subsections')->orderBy('survey_subsections.order')->pluck('id');
        
        $pom = [];
        foreach ($subsections as $sub) {
            $pom = Question::join('survey_qfor_question', 'survey_questions.id', '=', 'survey_qfor_question.question_id')
                            ->join('survey_subsections', 'survey_questions.subsection_id', '=', 'survey_subsections.id')
                            ->with('question_column')
                            ->where('survey_questions.subsection_id', $sub)
                            ->where('survey_qfor_question.qfor_id', '=', $who)
                            ->orderBy('survey_questions.orderQ')
                            ->orderBy('survey_questions.created_at')
                            ->get();
            if (count($pom) !== 0) { 
                $sections[] = $pom;
            }   
        }
        return $sections;
        //one array for qtype id 1, second qtype id 2
    }

    public function demoQuestions($who){
        $this->id = $who;
        $questions = Question::with('option')
                        ->whereIn('qtype_id', array(4,5))
                        ->whereHas('qfor', function($query){
                            $query->where('survey_qfors.id',$this->id);
                        })
                        ->get();

        return $questions;
    }

    public function getDescriptions(){
        $descriptions = Qfor::select('id', 'description')->get();
        return $descriptions;
    }

    public function getIntro($who) {
        //$desc = Qfor::find(3)->select('description')->get();
        $desc = Qfor::where('id',$who)->select('description')->get();
        return $desc;
    }

    public function determineWHO($name) {
        switch ($name) {
            case 'student':
                return 1;
            case 'teacher':
                return 2;
            case 'management':
                return 3;
            case 'researcher':
                return 4;                
            default:
                return 0;
        }
    }
}
