<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;

use App\Subresult; // no longer used
use App\Result; //no longer used

use App\Result_Question;
use App\Person;
use App\Test_Score;
use App\Section_Score;
use App\Subsection;
use App\Option;
use App\Question;
use App\Suboption;

class SurveyController extends Controller
{
    public function index(){
        return view('pages.index');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        #VALIDATION
        $validated = [];
        #validation method that makes sure that all inputs from user are required
        foreach($request->except('_token') as $data => $value){
            $validated[$data] = 'required';
        }
        if (isset($inputs['agreement'])){
            $request->validate($validated);
        }


        #CALCULATION AFTER VALIDATION SUCCEEDS
        #calculates score of a filled survey

        #to array
        $inputs = $request->toArray();
        //dd($inputs);

        #function 'calculation' calculates the score of every section and returns array [number] => [points]
        $scores = $this->calculation($inputs);

        $sections = Subsection::orderBy('order')->get();
        $sectionValues = []; //is it neccessary?
        foreach ($sections as $sec) {
            $sectionValues[$sec->id] = $sec->value;
        }

        #calculates the score for whole survey (one number)
        $totalResult = 0;
        foreach ($scores as $key => $value) {
            $pom = $sectionValues[$key]*$scores[$key];
            $totalResult += $pom;
        }
        // dd($totalResult);

        #############
        #### store into the db
        ########
        if (isset($inputs['agreement'])) {
            $person = $this->newPerson($request);
            $this->newResult($person->id, $inputs);
            $this->saveDemographicData($person->id, $inputs);
        }
        #########

        #renders final responses to the user given the score
        $finaltext = $this->getSurveyResponse($totalResult, $inputs['who']);

        #renders section responses to the user given the score
        $texts = [];
        foreach ($scores as $idsection => $score) {
            $text = $this->getSectionResponse($score, $idsection, $inputs['who']);
            $texts[] = $text;
        }

        #option feedbacks
        $feedbacks = $this->getOptionFeedbacks($inputs);

        #max pts obtainable
        $max = $inputs['totalMaxPts'];
        $ptsRecieved = $inputs['totalPts'];

        // return view('pages.finished')->with(['texts' => $texts, 'finaltext' => $finaltext,
        //                                     'sections' => $sections, 'totalresult' => $totalResult, 'feedbacks' => $feedbacks]);
        return view('dev.finished')->with(['texts' => $texts, 'finaltext' => $finaltext, 'ptsRecieved' => $ptsRecieved, 'scores' => $scores,
                                            'sections' => $sections, 'totalresult' => $totalResult, 'feedbacks' => $feedbacks, 'max' => $max]);
    }
    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show()
    {
        // return to home, trting to enter the route for final results without filling out the form
        return view('pages.index');
    }

    ##NOT WORKING##
    private function calculation($inputs){
        #get all section ids
        $sections = DB::table('survey_subsections')->pluck('id');
        #number of sections
        $numSec = count($sections);
        $capTest = 0;
        for ($i=0; $i < $numSec; $i++) {
            $currentIdQ = ''; //empty array for every section
            $qsUsed = [];
            $id_section = '';
            $uncheckedScores = 0;
            $count = 0;
            $prevQ = NULL;
            $prefix = $sections[$i];
            $pattern = '/^'.$prefix.'\.*/i'; #regex: id_section + anything, any count; case insensitive
            $keys = array_keys($inputs); #keys from array, e.g. "age", "16_11",..
            $allowed = preg_grep($pattern, $keys); #filter keys, we want only those that start with correct id_section
            $results = array_intersect_key($inputs, array_flip($allowed)); #function a_i_k_() compares the keys of two (or more) arrays, and returns the matches
            #now in $results we have filtered inputs, only those connected to current id_section

            
            $pom = 0;
            foreach ($results as $key => $result) { //dd($results);
                $count += 1;
                #one result is one score from one question + space + id_option of selected option
                #split it by space
                list($value, $id_option) = explode("_", $result);
                #get id section and id question
                list($id_section, $id_question) = explode("_", $key);
                $qsUsed[] = $id_question;
                $pom += $value;
                if (empty($currentIdQ)) {$currentIdQ = $id_question;}


                // test if $question is type 2
                if ($this->isQtypeTwo($id_question)) {
                    // test if pts are over capped (only at qs type 2)
                    //TODO:
                    // same q?
                    // if ($id_question == $currentIdQ) {
                    //     $capTest += $value;

                    // } else if ($prevQ != NULL) {
                    //     // find q $currentIdQ and get capped value
                    //         $q = Question::find($prevQ);

                    //         if ($q->capPts != NULL && $q->capPts < $capTest) {
                    //             $pom -= $capTest - $q->capPts;
                    //         }
                    //         $capTest = 0;
                    // }
                    // //last item in section with capp pts
                    // if ($count == count($results)) {
                    //     $q = Question::find($currentIdQ);
                    //     if ($q->capPts != NULL && $q->capPts < $capTest) {
                    //         $pom -= $capTest - $q->capPts;
                    //     }
                    //     $capTest = 0;
                    // }

                    // add all unchecked scores
                    if ($uncheckedScores == 0){
                        $uncheckedScores = $this->getUncheckedValues($id_question);
                    } else if ($currentIdQ != $id_question) {
                        // another question
                        $pom += $uncheckedScores;
                        $uncheckedScores = $this->getUncheckedValues($id_question);
                    }
                    // subtract unchecked value
                    $uncheckedScores -= $this->getUncheckedValue($id_option, $id_question);

                    // has one feedback for the whole question based on pts check
                    // hasOneFeedback($id_question);
                } else {
                    $pom += $uncheckedScores;
                    $uncheckedScores = 0;
                }

                if ($id_question != $currentIdQ) {
                    $prevQ = $currentIdQ;
                    $currentIdQ = $id_question; // test this somewhere??
                }
            }
            // fetch all questions to the section with qtype_id 2
            //dd($inputs);
            $qs = Question::where('qtype_id',2)->where('subsection_id', $id_section)->pluck('id')->toArray();
            // find difference in two arrays $qs and $results
            $notRegisteredQs = array_diff($qs, $qsUsed);
            // get all options with not_checked values and add them
            $sum = 0;
            if (!empty($notRegisteredQs)) {
                foreach ($notRegisteredQs as $key => $qid) {
                //optimize this
                    $opts = Option::join('survey_questions', 'survey_questions.id', 'survey_options.question_id')->where('survey_questions.id', $qid)->get();
                    //dd($opts);
                    foreach ($opts as $opt) {
                        $sum += $opt->value_not_checked*$opt->weight; //*q->weight
                    }
                }
            }
            // add those points from questions type 2, that hasnt been calculated yet
            $pom += $sum;
            // add those points for unchecked options
            if ($uncheckedScores > 0) {
                $pom += $uncheckedScores;
            }
            // final points for a section
            $scores[$sections[$i]] = $pom;

        }
        //dd($scores);
        return $scores;
    }

    private function isQtypeTwo($idq){
        $q = Question::find($idq);

        return $q->qtype_id == 2;
    }

    private function hasOneFeedback($idq){
        $q = Question::find($idq);

        if ($q->hasFeedback) {

        }

        return;
    }

    private function getUncheckedValues($idq){
        //fetch all option
        $sum = 0;
        $opts = Option::join('survey_questions', 'survey_questions.id', '=', 'survey_options.question_id')
                ->where('question_id', $idq)->get();
        foreach($opts as $opt){
            if ($opt->value_not_checked > 0){
                $sum += $opt->value_not_checked*$opt->weight; //* question weight
            }
        }
        return $sum;
    }

    private function getUncheckedValue($ido, $idq){
        $opt = Option::find($ido);
        $q = Question::find($idq);
        if ($opt->value_not_checked > 0){
            return $opt->value_not_checked*$q->weight; // * question weight !
        }
        return 0;
    }

    private function getOptionFeedbacks($inputs){
        #get all section ids
        $sections = DB::table('survey_subsections')->pluck('id');
        #number of sections
        $numSec = count($sections);

        for ($i=0; $i < $numSec; $i++) {
            $prefix = $sections[$i];
            $pattern = '/^'.$prefix.'\.*/i'; #regex: id_section + anything, any count; case insensitive
            $keys = array_keys($inputs); #keys from array, e.g. "age", "16_11",..
            $allowed = preg_grep($pattern, $keys); #filter keys, we want only those that start with correct id_section
            $results = array_intersect_key($inputs, array_flip($allowed)); #function a_i_k_() compares the keys of two (or more) arrays, and returns the matches

            #now in $results we have filtered inputs, only those connected to current id_section
            $pom = '';
            $options = [];
            $feedbacks = [];
            $opts = [];
            foreach ($results as $result) {
                list($value, $id_option) = explode("_", $result);
                $option = Option::find($id_option);

                $option = DB::table('survey_options')
                            ->where('survey_options.id', $id_option)
                            ->join('survey_questions', 'survey_questions.id', '=', 'survey_options.question_id')
                            // column value is here only to be replaced by value that user has chosen, we do not need option.id
                            ->select('survey_questions.hasFeedback AS hasFeedback', 'survey_options.id AS value', 'survey_options.feedback AS feedback', 'survey_questions.id AS id_question', 'survey_options.id AS id_option', 'survey_questions.text AS question', 'survey_options.name AS selectedOption', 'survey_questions.qtype_id AS qtype_id')
                            ->get();
                //spocitat value here?
                //dosadit a preskocit na dalsi otazku
                if ($option[0]->qtype_id === 3){
                    list($value, $id_option, $userChoice) = explode("_", $result);
                    $option[0]->value = $userChoice;
                }
                $option = $option[0];
                if ($option->feedback != "") {
                    $options[] = $option;
                }
            }
            //add one feedback to Question here?
            //
            //
            //$feedbacks[$option->id_question]->oneFeedback = $calculatedFeedback
            //


            // is there any feedback?
            if (!empty($options)){
                foreach ($options as $option){
                    $feedbacks[$option->id_question][] = $option;
                }
                $sectionFeedbacks[] = $feedbacks;
            }else{
                $sectionFeedbacks[] = [];
            }
        }

        return $sectionFeedbacks;
    }

    public function newPerson($request){
        $person = new Person;
        $person->age = $request->age;
        $person->country = $request->country;
        $person->q_for_id = $request->who;
        $person->gender = $request->gender;
        $person->save();

        return $person;
    }

    # save survey result
    public function newResult($person_id, $inputs){

        $keys = array_keys($inputs); #keys from array, e.g. "age", "16_11",..
        $allowed = preg_grep('/^\d.*/i', $keys); #only those starting with digit in key
        $results = array_intersect_key($inputs, array_flip($allowed)); #filter $inputs to only inputs with question data

        foreach ($results as $key => $result) {
            $keySplitValues = explode("_", $key);
            $question_id = $keySplitValues[1];

            $resultSplit = explode("_", $result);
            $points = $resultSplit[0];
            $option_id = $resultSplit[1];
            $onetofiveSelected = NULL;
            // test if the question is 1-5 type or not
            if (count($resultSplit) == 3) {
                $onetofiveSelected = $resultSplit[2];
            }
            // dd($keySplitValues);
            $result = new Result_Question;

            $result->question_id = $question_id;
            $result->option_id = $option_id;
            $result->person_id = $person_id;
            $result->one_to_five_selected = $onetofiveSelected;
            $result->points = $points;

            $result->save();
        }
    }

    public function saveDemographicData($person_id, $inputs){
        $keys = array_keys($inputs); #keys from array, e.g. "age", "16_11",..
        $allowed = preg_grep('/^demo.*/i', $keys); #only those starting with digit in key
        $results = array_intersect_key($inputs, array_flip($allowed)); #filter $inputs to only inputs with question data

        foreach ($results as $key => $result) {
            $keySplitValues = explode("_", $key);
            $question_id = $keySplitValues[1];

            $resultSplit = explode("_", $result);
            $points = NULL;
            $option_id = NULL;
            $onetofiveSelected = NULL;

            // test if the demographic question was just plain text or select type (in which we need to store the answer selected)
            if (count($resultSplit) == 2) {
                $option_id = $resultSplit[1];
            }

            $result = new Result_Question;

            $result->question_id = $question_id;
            $result->option_id = $option_id;
            $result->person_id = $person_id;
            $result->one_to_five_selected = $onetofiveSelected;
            $result->points = $points;

            $result->save();
        }
    }

    public function getSurveyResponse($score, $who){
        //find in db accurate testresponse
        $response = Test_Score::where('min_pts', '<', $score)
                        ->where('qfor_id', $who)
                        ->orderBy('min_pts', 'desc')
                        ->take(1)
                        ->get();

        return $response;
    }

    public function questionMaxPts($qid) {
        $question = Question::find($qid);
        $options = Option::where("question_id", $qid)->get();
        $max = 0;
        //type 1 of many
        if ($question->qtype_id == 1) {
            foreach ($options as $key => $option) {
                $option->value > $max ? $max = $option->value : null;
            }
            return $question->weight * $max;
        } else if ($question->qtype_id == 2) {
            $total = 0;
            foreach ($options as $key => $option) {
                if ($option->value < $option->value_not_checked) {
                    $value = $option->value_not_checked;
                } elseif ($option->value > 0){
                    $value = $option->value;
                } else {
                    $value = 0;
                }
                $total += $value;
            }
            return $question->weight * $total;
        } else if ($question->qtype_id == 3) {
            $total = 0;
            foreach ($options as $key => $option) {
                $suboptions = Suboption::where("option_id", $option->id)->get();
                $max = 0;
                foreach ($suboptions as $keySub => $suboption) {
                    $suboption->value > $max ? $max = $suboption->value : null;
                }
                $total += $max;
            }
            return $question->weight * $total;
        }
        return 0;
    }

    public function getSectionResponse($score, $idsection, $who){
        if($score === 0){
            return [];
        }else{
            $response = DB::table('survey_section_scores')
                        ->join('survey_subsections', 'survey_subsections.id', '=', 'survey_section_scores.subsection_id')
                        ->where('subsection_id', $idsection)
                        ->where('min_pts', '<', $score)
                        ->where('qfor_id', $who)
                        ->orderBy('min_pts', 'desc')
                        ->take(1)
                        ->get();

            return $response;
        };
    }
}
