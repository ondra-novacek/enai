<?php

namespace App\Http\Controllers;
use App\Http\Controllers\PagesController;
use App\Http\Controllers\SurveyController;

use App\Option;
use App\Question;
use App\Qtype;
use App\Subsection;
use App\Qfor_Question;
use App\Qfor;
use App\Subresult;
use App\Result;
use App\Section_Score;
use App\Test_Score;
use App\Result_Question;
use App\Suboption;

use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use App\Question_column;
// use Illuminate\Support\Facades\Request;
// use Symfony\Component\HttpFoundation\Request;

class ApiController extends Controller
{
    #gives all neccesary data to form for edit
    public function DownloadAll($id){
        $PgsC = new PagesController();
        $whoName = $PgsC->who($id);
        $sections = $PgsC->sections($id);
        $options = $PgsC->options($id);
        return [$whoName, $sections, $options];
    }

    #all surveys
    public function Surveys(){
        $neco = new PagesController();
        $surveys = $neco->surveysfor();
        return $surveys;
    }

    #name of the group that the survey is aim for
    public function SurveyFor($id){
        $PgsC = new PagesController();
        $whoName = $PgsC->who($id);
        return $whoName;
    }

    #list of sections with questions
    public function Sections($id){
        // $PgsC = new PagesController();
        // $sections = $PgsC->sections($id);
        $sections = $this->sectionsApi($id);
        return $sections;
    }

    #listof options for questions
    public function Options($id){
        $PgsC = new PagesController();
        $options = $PgsC->options($id);
        return $options;
    }

    #delete option by id
    public function DeleteOption($id){
        Option::destroy($id);
    }

    #add suboption
    public function addSuboption(Request $request){
        $newSuboption = new Suboption;

        $newSuboption->option_id = $request->option_id;
        $newSuboption->columnNum = $request->columnNum;
        $newSuboption->name = 'test';
        $newSuboption->feedback = '';
        $newSuboption->value = 0;

        $newSuboption->save();
    }

    #edit suboption
    public function editSuboption(Request $request){
        $suboption = Suboption::find($request->id);
        $suboption->feedback = $request->feedback;
        $suboption->save();
    }

    #edit suboption value
    public function editSuboptionValue(Request $request){
        $suboption = Suboption::find($request->id);
        $suboption->value = $request->value;
        $suboption->save();
    }

    #add option give question id
    public function AddOption(Request $request){
        $newOption = new Option;

        $newOption->name = $request->name;
        $newOption->value = $request->points;
        $newOption->question_id = $request->question_id;
        $newOption->feedback = '';
        if (!empty($request->pointsNC)) {
            $newOption->value_not_checked = $request->pointsNC;
        }

        $newOption->save();

        if($request->qtype == 3){
            for ($i=1; $i <= $request->cols; $i++) { 
                $newSuboption = new Suboption;

                $newSuboption->option_id = $newOption->id;
                $newSuboption->columnNum = $i;
                $newSuboption->name = 'test';
                $newSuboption->feedback = '';
                $newSuboption->value = 0;

                $newSuboption->save();
            }
        }
    }

    #edit option
    public function EditOption(Request $request){
        $option = Option::find($request->id);

        $option->value = $request->value;
        $option->value_not_checked = $request->valueNC;
        $option->name = $request->name;

        $option->save();
    }

    #edit option feedback
    public function EditOptionFeedback(Request $request){
        $option = Option::find($request->id);

        $option->feedback = $request->feedback;

        $option->save();
    }

    public function EditOptionFeedbackNotSel(Request $request){
        $option = Option::find($request->id);

        $option->feedback_not_checked = $request->feedback;

        $option->save();
    }

    #add one feedback to question
    public function addOneFeedback(Request $request){
        $question = Question::find($request->idq);

        $question->hasFeedback = $request->useThis;
        $question->feedbackSplitValue = $request->splitPts;
        $question->feedbackOriginal = $request->fbOriginal;
        $question->feedbackAlt = $request->fbAlt;
        $question->equalSignOriginal = $request->eqSignOriginal;


        $question->save();
    }

    #add new question
    public function AddNewQuestion(Request $request){
        $newQuestion = new Question;

        $newQuestion->text = $request->text;
        $newQuestion->weight = $request->weight;
        $newQuestion->qtype_id = $request->qtype_id;
        $newQuestion->subsection_id = $request->section;
        $newQuestion->rateTo = $request->rateTo;

        //find max order in that section
        $max = Question::where('subsection_id', $request->section)->max('orderQ');
        if (!empty($max)){
            $max += 1;
        } else {
            $max = 1;
        }
        $newQuestion->orderQ = $max;

        $newQuestion->save();

        //add question to survey
        $QtoSurvey = new Qfor_Question;

        $QtoSurvey->question_id = $newQuestion->id;
        $QtoSurvey->qfor_id = $request->whofor;

        $QtoSurvey->save();
    }

    #add exsting question to a survey
    public function AddExistingQuestion(Request $request){
        $QtoSurvey = new Qfor_Question;

        $QtoSurvey->question_id = $request->question_id;
        $QtoSurvey->qfor_id = $request->whofor;

        $QtoSurvey->save();
    }

    #add or edit column in question
    public function editColumnValue(Request $request){
        // return $request->value;
        $column = Question_column::where('question_id', $request->question_id)->where('columnNum', $request->index)->first();
        // return $column;
        if (sizeof($column) > 0) {
            $column->name = $request->value;
            $column->save();
        } else {
            $column = new Question_column();

            $column->question_id =  $request->question_id;
            $column->name = $request->value;
            $column->columnNum = $request->index;

            $column->save();
        }
    }

    #get demographic questions
    public function getDemographicQuestions($id){
        $this->id = $id;
        $qs = Question::with('option')
                ->whereHas('qfor', function($query){
                        $query->where('survey_qfors.id',$this->id);
                    })
                ->whereIn('qtype_id', array(4,5)) // 4,5 are types only used in demo
                ->where('subsection_id', 1) // 1 = demosection
                ->get();
        
        return $qs;
    }

    #edit question text
    public function EditQuestionText(Request $request){
        $question = Question::find($request->id);
        $question->text = $request->text;

        $question->save();
    }

    #edit question value
    public function EditQuestionValue(Request $request){
        $question = Question::find($request->id);
        $question->weight = $request->value;

        $question->save();
    }

    #edit question type
    public function EditQuestionType(Request $request){
        $question = Question::find($request->id);
        $question->qtype_id = $request->qtype_id;

        $question->save();
    }

    #edit question rateTo
    public function EditQuestionRateTo(Request $request){
        $question = Question::find($request->id);
        $question->rateTo = $request->rateTo;

        $question->save();
    }

    #change subsection in question
    public function changeQuestionSection(Request $request){
        $question = Question::find($request->idq);
        $question->subsection_id = $request->ids;
        $question->save();
    }

    #edit row text
    public function editRowText(Request $request){
        $opt = Option::find($request->option_id);
        $opt->name = $request->name;
        $opt->save();
    }

    #update note in question
    public function updateNote(Request $request){
        $question = Question::where('id',$request->idq)->first();
        $question->note = $request->note;
        $question->save();
    }

    #delete option row
    public function deleteOptionRow(Request $request){
        Option::find($request->id)->delete();
    }

    #qtype fetch
    public function QtypeFetch(){
        $qtypes = Qtype::all();

        return $qtypes;
    }

    #subsections fetch
    public function SubsectionsFetch(){
        $sections = Subsection::all();

        return $sections;
    }

    #other questions fetch
    public function otherQuestionsFetch(Request $request){
        $id = $request->idwho;
        $ids = $request->idsection;
        #get all question that are in a survey for $id
        $idq = Qfor_Question::where('qfor_id', '=', $id)->pluck('question_id');
        #filter all question that are not used in a survey for $id and that are in the same subsection
        $qs = Question::whereNotIn('id', $idq)->where('subsection_id', $ids)->get();
        #return questions
        return $qs;
    }

    #edit survey description
    public function editSurveyDesc(Request $request){
        $survey = Qfor::find($request->id);
        $survey->description = $request->text;

        $survey->save();
    }

    #get survey desc
    public function getDescription($id){
        $desc = Qfor::find($id);
        return $desc;
    }

    #add new section
    public function addNewSection(Request $request){
        $section = new Subsection;

        $section->value = $request->weigth;
        $section->name = $request->name;
        $section->order = Subsection::max('order')+1;

        $section->save();
    }

    #delete question from db
    public function deleteQuestionById($id){
        //smazat napojeni
        $qforq = Qfor_Question::where('question_id', $id)->pluck('id');
        Qfor_Question::destroy($qforq);

        //smazat moznosti k otazce
        $options = Option::where('question_id', $id)->delete();

        //smazat odpoved
        $question = Question::where('id', $id)->delete();
    }

    #deletes question
    public function deleteQuestion(Request $request){
        $number = Qfor_Question::where('question_id', $request->idq)->count();
        if ($number > 1){
            //delete connection
            $qforq = Qfor_Question::where('question_id', $request->idq)->where('qfor_id', $request->idwho)->delete();
        }else{
            //delete question
            $this->deleteQuestionById($request->idq);
        }

    }

    #edit section data
    public function editSection(Request $request){
        $section = Subsection::find($request->section_id);

        if ($request->name != ''){
            $section->name = $request->name;
        }
        if ($request->value != ''){
            $section->value = $request->value;
        }

        $section->save();
    }

    #deletes section, but only if the section is completely unused
    public function deleteSection(Request $request){
        #check if it can be deleted
        $id = $request->section_id;
        $questions = Question::where('subsection_id', $id)->get();
        if (count($questions) > 0){
            #cannot be deleted
            return "The section is being used. Cannot be deleted. Remove all questions connected to this section and then try deleting again.";
        }else{
            #deletion 
            $section = Subsection::find($id)->delete();
            return "Section has been successfully deleted.";
        }

    }

    public function deleteAllResults(){
        // DB::table('survey_subresults')->delete();
        // DB::table('survey_results')->delete();
        DB::table('survey_result_questions')->delete();
    }

    #edit evaluation section
    public function editEval(Request $request){
        $eval = Section_Score::find($request->id);

        $eval->text = $request->text;

        $eval->save();
    }

    #edit evaluation final
    public function editEvalFinal(Request $request){
        $eval = Test_Score::find($request->id);

        $eval->text = $request->text;

        $eval->save();
    }

    #swaps order of 2 sections
    public function swapSectionOrder(Request $request){
        $firstId = $request->first['subsection_id'];
        $firstOrder = $request->first['order'];
        $secondId = $request->second['subsection_id'];
        $secondOrder = $request->second['order'];

        $first = Subsection::find($firstId);
        $first->order = $secondOrder;
        $second = Subsection::find($secondId);
        $second->order = $firstOrder;

        $first->save();
        $second->save();
    }

    #swaps order of 2 sections
    public function swapQuestionOrder(Request $request){
        $firstId = $request->first['question_id'];
        $firstOrder = $request->first['orderQ'];
        $secondId = $request->second['question_id'];
        $secondOrder = $request->second['orderQ'];

        $first = Question::find($firstId);
        $first->orderQ = $secondOrder;
        $second = Question::find($secondId);
        $second->orderQ = $firstOrder;

        $first->save();
        $second->save();
    }

    private function skippedQsMax($qs){
        $sum = 0;
        foreach ($qs as $key => $qid) {
            $qs = Question::join('survey_options', 'survey_options.question_id', '=', 'survey_questions.id')->where('survey_questions.id', $qid)->get();

            if (empty($qs)) {continue;}
            $maxPts = 0;
            //if type 1
            if ($qs[0]->qtype_id == 1) {
                foreach ($qs as $key => $option) {
                    if ($option->value > $maxPts) {
                        $maxPts = $option->value;
                    }
                }
                $sum += $maxPts*$qs[0]->weight;
            }
            //if type 2
            if ($qs[0]->qtype_id == 2) {
                foreach ($qs as $key => $option) {
                    $value = ($option->value > $option->value_not_checked) ? $option->value : $option->value_not_checked;
                    $sum += $value*$qs[0]->weight;
                }
            }
            //if type 3
            if ($qs[0]->qtype_id == 3) {
                $total = 0;
                foreach ($qs as $key => $option) {
                    $options = Option::join('survey_suboptions', 'survey_options.id', '=', 'survey_suboptions.option_id')->where('survey_options.id', $option->id)->get()->toArray();
                    $max = 0;
                    foreach ($options as $k => $suboption) {
                        $max = $max > $suboption['value'] ? $max : $suboption['value'];
                    }
                    $total += $max;
                    //return $total;
                }
                //missing qvalue, not important now
                $sum += $total;
            }
        }
        return $sum;
    }

    #evaluate one section, send back feedbacks
    public function evaluateOneSection(Request $request){
        $eval = [];
        $question = [];
        $alreadyAdded = [];
        $opts = [];
        $data = $request->selectedOptions;
        $forgotten = $request->forgottenQs;
        $skipped = $request->qsSkipped;
        $prevIdq = 0;

        //zjistit pro kazdou otazku (a typ) jakej je jejich max pts (celkovy soucet)
        $skippedQsMax = $this->skippedQsMax($skipped);
        
        foreach ($data as $value) {
            // get id option as first value and possibly option value as second value (if qtype was 'rate 1-5' type of question)
            $parts = explode("_", $value);
            
            // get info needed for feedback display
            // test if the option is coming from 'rate 1-5' type of question
            if (array_key_exists(1, $parts)){
                // if so, we need to treat it a bit differently, since its actually question
                $result = DB::table("survey_options")
                ->join("survey_questions", "survey_options.question_id", "survey_questions.id")
                ->join("survey_subsections", "survey_subsections.id", "survey_questions.subsection_id")
                ->leftJoin("survey_suboptions", "survey_suboptions.option_id", "survey_options.id") #added recently
                ->where("survey_options.id", "=", $parts[0])
                ->where("survey_suboptions.columnNum", $parts[1])
                ->select(DB::raw('1 as selected'), "survey_questions.hasFeedback AS hasFeedback", "survey_suboptions.feedback AS feedback"
                    , "survey_questions.note AS note", "survey_subsections.name AS subsection", "survey_questions.qtype_id AS qtype"
                    , "survey_questions.id AS qid", "survey_options.id AS oid", "survey_questions.text AS question", "survey_questions.weight AS qweight"
                    , "survey_options.value AS ovalue", "survey_suboptions.value AS sovalue", "survey_subsections.id AS sid"
                    , "survey_options.name AS option", "survey_questions.capPts as cap", "survey_questions.created_at AS created_at")
                ->get();
                
                // no feedback, go to next iteration
                if (sizeof($result) < 1) {continue;}
                // points = question weight * option value * suboption value
                $result[0]->pts = $result[0]->qweight * $result[0]->ovalue * $result[0]->sovalue;

                 // max points per row
                 // for displaying purposes, so the user can see s/he received X pts out of maxPts
                 $maxPtsPerLine = 0;
                 $subQuestions = Suboption::where("option_id", $parts[0])->get();
                 foreach ($subQuestions as $subquestion) {
                     if ($subquestion->value > $maxPtsPerLine) {
                         $maxPtsPerLine = $subquestion->value;
                     }
                 }
                 $result[0]->maxPtsPerLine = $maxPtsPerLine * $result[0]->qweight;

                // convert number of col into real column name
                // if that fails, get at least the number
                $value = DB::table("survey_question_columns")
                  ->where("question_id", $result[0]->qid)
                  ->where("columnNum", $parts[1])
                  ->get();
                if (sizeof($value) < 1) {
                  $result[0]->value = $parts[1];  
                } else {
                  $result[0]->value = $value[0]->name;
                }

            } else {
                //question type 1 or 2
                $result = DB::table("survey_options")
                ->join("survey_questions", "survey_options.question_id", "survey_questions.id")
                ->join("survey_subsections", "survey_subsections.id", "survey_questions.subsection_id") 
                ->where("survey_options.id", "=", $parts[0])
                ->select(DB::raw('1 as selected'), "survey_questions.note AS note"
                ,"survey_questions.hasFeedback AS hasFeedback", "survey_questions.feedbackSplitValue AS splitPts", "survey_questions.feedbackOriginal AS originalFB", "survey_questions.feedbackAlt AS altFB", "survey_questions.equalSignOriginal AS equalSign"
                ,"survey_questions.weight AS weight", "survey_options.value AS value", "survey_questions.qtype_id AS qtype" ,"survey_subsections.name AS subsection"
                , "survey_questions.id AS qid", "survey_options.id AS oid", "survey_questions.text AS question", "survey_options.feedback AS feedback", "survey_subsections.id AS sid"
                , "survey_options.name AS option", "survey_questions.capPts as cap", "survey_options.value_not_checked AS value_not_checked", "survey_questions.created_at AS created_at")
                ->get();

                $result[0]->pts = $result[0]->weight*$result[0]->value;   

                // if (in_array($result[0]->qid, $skip)){
                //     continue;
                // }
            }

            // test whether it's question type 2 (any from many) - in that case, return all feedbacks to that question
            // && test whether that unchecked options for specific question have already been added (2nd condition)
            if ($result[0]->qtype === 2 && !array_key_exists($result[0]->qid, $alreadyAdded)){
                $alreadyAdded[$result[0]->qid] = true;
                // fetch all options to the question
                $opts = Option::where('question_id', $result[0]->qid)->pluck('id')->toArray();
                
                // filter those that are checked by user
                $optsResults = array_diff($opts, $data);
                
                // fetch those options with other important data
                // we need feedback_not_checked
                $opts = DB::table("survey_options")
                ->join("survey_questions", "survey_options.question_id", "survey_questions.id")
                ->join("survey_subsections", "survey_subsections.id", "survey_questions.subsection_id") 
                ->whereIn("survey_options.id", $optsResults)
            # this is commented temp, we want to display all questions, even those without feedback
                // ->where(function($q) {
                //     $q->where("survey_options.feedback_not_checked", "<>", "")
                //       ->orWhere("survey_questions.hasFeedback", 1);
                // })
                ->select(DB::raw('0 as selected'), "survey_questions.note AS note"
                ,"survey_questions.hasFeedback AS hasFeedback", "survey_questions.feedbackSplitValue AS splitPts"
                , "survey_questions.feedbackOriginal AS originalFB", "survey_questions.feedbackAlt AS altFB"
                , "survey_questions.equalSignOriginal AS equalSign", "survey_questions.qtype_id AS qtype", "survey_subsections.name AS subsection"
                , "survey_questions.id AS qid", "survey_options.id AS oid", "survey_questions.text AS question"
                , "survey_options.feedback_not_checked AS feedback", "survey_options.value_not_checked AS value_not_checked", "survey_questions.capPts as cap", "survey_options.value AS value"
                , "survey_subsections.id AS sid", "survey_options.name AS option", "survey_questions.created_at AS created_at")
                ->get();
                
               $optsNotSelected = Option::whereIn("id", $optsResults)->get();
               if (count($optsNotSelected) > 0) {
                    $questionHelper = Question::find($optsNotSelected[0]->question_id);
                    $total = 0;
                    foreach ($optsNotSelected as $option) {
                        $total += $option->value_not_checked * $questionHelper->weight;  
                    }
                    $result[0]->pts += $total;
               }
                
            }
            
            //speed up the unnecessary iterations
        # this is commented temp, we want to display all questions, even those without feedback
            // if ( ($result[0]->feedback == "" && sizeof($opts) < 1) 
            //         && (!$result[0]->hasFeedback) && ($result[0]->pts == 0)) {continue;}
            
            // group together options with same question id into one array
            if ($prevIdq !== $result[0]->qid) {
                if (!empty($question)){
                    $eval[] = $question;
                    $question = [];  
                }
                // test so we can add unchecked options in question type 2
                if (!empty($opts)){
                    foreach ($opts as $option) {
                        $option->pts = 0;
                        $question[] = $option;
                    }
                    $opts = [];
                }
            } 
            $question[] = $result[0];
            

            // keep the previous question id for grouping purposes
            $prevIdq = $result[0]->qid;
            //continue;
        }

        // test if the last value in cycle was or was not added. if not, add it
        if (!empty($question)) {
            $eval[] = $question;
        }

        #needed now
        // include forgotton questions
        // return $forgotten;
        $question = [];
        foreach ($forgotten as $questionId) {
            $opts = DB::table("survey_options")
                ->join("survey_questions", "survey_options.question_id", "survey_questions.id")
                ->join("survey_subsections", "survey_subsections.id", "survey_questions.subsection_id") 
                ->where("survey_questions.id", $questionId)
                // ->where(function($q) {
                //      $q->where("survey_options.feedback_not_checked", "<>", "")
                //        ->orWhere("survey_questions.hasFeedback", 1);
                //  })
                // ->where("survey_options.feedback_not_checked", "<>", "") //filter those without feedback
                //->orWhere("survey_questions.hasFeedback", "1")
                ->select(DB::raw('0 as selected'), DB::raw('0 as pts'), "survey_options.value_not_checked AS value_not_checked", "survey_questions.weight AS qweight", "survey_options.value AS value"
                    ,"survey_questions.hasFeedback AS hasFeedback", "survey_questions.feedbackSplitValue AS splitPts", "survey_questions.feedbackOriginal AS originalFB", "survey_questions.feedbackAlt AS altFB", "survey_questions.equalSignOriginal AS equalSign"
                    , "survey_questions.qtype_id AS qtype", "survey_subsections.name AS subsection", "survey_questions.id AS qid", "survey_options.id AS oid", "survey_subsections.id AS sid"
                    , "survey_questions.text AS question", "survey_options.feedback_not_checked AS feedback"
                    , "survey_options.name AS option", "survey_questions.capPts as cap", "survey_questions.created_at AS created_at")
                ->get();
            //return [$forgotten,$opts];
            //sum of pts for question without feedback
            $sumWOfb = 0;
            if (count($opts) > 0){
                foreach ($opts as $option) {
                    $option->pts = $option->value_not_checked * $option->qweight;
                    //if it doesnt have fb, we need only pts and not add it to qs
                # this is commented temp, we want to display all questions, even those without feedback
                    // if (!$option->hasFeedback && empty($option->feedback)){
                    //     $sumWOfb += $option->pts;
                    //     continue;
                    // }
                    $question[] = $option;
                }  
                if (!isset($question[0])) {
                    $question[0] = $opts[0];
                    $question[0]->pts += $sumWOfb;
                } else {
                    $question[0]->pts += $sumWOfb;
                }
                $sumWOfb = 0;
            } else {
                // NS = not selected
                $questionNS = Question::find($questionId);
                $opts = Option::where('question_id', $questionId)->get();
                $total = 0;
                foreach ($opts as $option) {
                    $total += $option->value_not_checked * $questionNS->weight;
                }  
                                              
                $questionNS->pts = $total;
                $questionNS->question = $questionNS->text;
                $questionNS->selected = false;
                $questionNS->value = 'No option selected.';
                $questionNS->option = 'No option selected.';
                $questionNS->qid = $questionId;
                $question[0] = $questionNS;
            }
            $eval[] = $question;
            $question = [];
        }

        // final pts
        $totalPts = 0;
        $totalMaxPts = 0;
        $sc = new SurveyController();
        foreach ($eval as $question) {
            $question[0]->max = $sc->questionMaxPts($question[0]->qid);
            $pom = 0;
            foreach ($question as $option) {
                    $pom += $option->pts;
            }
            
            if (isset($question[0])) {
                $question[0]->ptsFinal = $pom;
                if ($question[0]->cap > 0 && $pom > $question[0]->cap) {
                    $question[0]->ptsFinal = $question[0]->cap;
                    $question[0]->max = $question[0]->cap;
                } elseif ($question[0]->cap > 0) {
                    $question[0]->max = $question[0]->cap;
                }
            } else {
                $question[0] = new \stdClass();
                $question[0]->ptsFinal = $pom;
            }
            $totalPts += $question[0]->ptsFinal;
            $totalMaxPts += $question[0]->max;
        }

        // sort questions in required order (by created_at due to missing order values in many qs)
        for ($i=0; $i < count($eval); $i++) { 
            $pom = strtotime($eval[$i][0]->created_at);
            $eval[$i]['sort'] = $pom; 
        }
        $eval = collect($eval)->sortBy('sort')->values()->all();
        for ($i=0; $i < count($eval); $i++) { 
            unset($eval[$i]['sort']); 
        }
        
        //return stuff for whole section 
        if (isset($eval[0][0]) && !empty($eval[0][0])) {
            $sectionFeedback = $sc->getSectionResponse($totalPts, $eval[0][0]->sid, $request->who, $totalMaxPts, $skippedQsMax );    
        } else {
            $sectionFeedback = 'No evaluation. Please continue.';
        }
        $eval[] = [$sectionFeedback, $totalPts, $totalMaxPts, $skippedQsMax];

        // return data to user
        return $eval;
    }

    #create surveys
    public function createSurveys(){
        //qfors
        $student = new Qfor;
        $student->name = 'student';
        $student->save();
        
        $teacher = new Qfor;
        $teacher->name = 'teacher';
        $teacher->save();

        $management = new Qfor;
        $management->name = 'management';
        $management->save();

        //qtypes
        $one = new Qtype;
        $one->name = "One from many";
        $one->save();

        $many = new Qtype;
        $many->name = "Any from many";
        $many->save();

        $qrate = new Qtype;
        $qrate->name = "One to five rate";
        $qrate->save();
        //new qtypes
        $plain = new Qtype;
        $plain->name = "Text input";
        $plain->save();

        $select = new Qtype;
        $select->name = "Rollup select";
        $select->save();

        $demographicSection = new Section;
        $demographicSection->id = 1;
        $demographicSection->name = "Demographic section";
        $demographicSection->value = 0;
        $demographicSection->order = 0;
        $demographicSection->save();

    }

    #divides questions into sections, returns as an array, argument is for what survey it is
    #almost same as in PagesControler, here it also includes sections not used on survey (empty survey matches)
    public function sectionsApi($who){

        $sections = [];
        $notfound = [];
        $subsections = DB::table('survey_subsections')->orderBy('survey_subsections.order')->pluck('id');
        
        $pom = [];
        foreach ($subsections as $sub) { 

            $pom = Question::with('question_column')
                ->join('survey_qfor_question', 'survey_questions.id', '=', 'survey_qfor_question.question_id')
                ->join('survey_subsections', 'survey_questions.subsection_id', '=', 'survey_subsections.id') 
                ->where('survey_questions.subsection_id', $sub)
                ->where('survey_qfor_question.qfor_id', '=', $who)
                ->orderBy('survey_questions.orderQ')
                ->orderBy('survey_questions.created_at')
                ->get();    
            if (count($pom) !== 0) { 
                #sections with found questions in the survey
                $sections[] = $pom;
            }else{
                #check if it is new
                $pom = Subsection::join('survey_questions', 'survey_questions.subsection_id', '=', 'survey_subsections.id')->where('survey_subsections.id', $sub)->get()->toArray();
                empty($pom[0]) ? $isEmpty = '1' : $isEmpty = '0';
                #sections that have no question in the survey
                $notfound[] = DB::table("survey_subsections AS s") 
                    ->where('id', $sub) 
                    ->select("s.id AS subsection_id", 's.name', 's.value', 's.created_at', 's.updated_at', DB::raw($isEmpty . ' as new'))
                    ->get();    
            }   
        }
        #we add sections with no found questions to the end, so it displays on frontend as last
        foreach ($notfound as $value) {
            array_push($sections, $value);
        }

        // dd($sections);
        return $sections;
    }

    public function getSubmittedDates(){
        $results = Result_Question::where(DB::raw('YEAR(created_at)'),DB::raw('YEAR(NOW())'))->groupBy('person_id')->orderBy(DB::raw('MONTH(max(created_at))'))->select(DB::raw("MONTHNAME(max(created_at)) as date"))->get();
        
        foreach ($results as $result) {
            $months[] = $result->date;
        }

        $results = array_count_values($months);
        // $monthNum  = 3;
        // $dateObj   = DateTime::createFromFormat('!m', $monthNum);
        // $monthName = $dateObj->format('F'); // March
        return $results;
    }

    public function updateCapPts(Request $request){
        $question = Question::where('id',$request->idq)->first();
        $question->capPts = $request->cappts;
        $question->save();
    }

    #declare questions order in section
    public function declareQuestionsOrder(Request $request){
        $order = 0;
        $questions = Question::where('subsection_id', $request->id)->orderBy('created_at')->get();
        foreach ($questions as $index => $question) {
            $question->orderQ = $index + 1;
            $question->save();
            if ($question->id == $request->questionid) {
                $order = $question->orderQ;
            }
        }
        return $order;
    }

    public function getRespondentsByCountry(){
        $results = DB::select('SELECT p.country FROM survey_result_questions r JOIN survey_people p ON r.person_id = p.id GROUP BY r.person_id, p.country ORDER BY COUNT(p.country) DESC');

        $num = 0;
        foreach ($results as $result) {
            $countries[] = $result->country;
            $num += 1;
        }

        $results = array_count_values($countries);
        $count = count($results);
        if ($count >= 5) {
            $results = array_slice($results, 0, 4);
            $pom = 0;
            foreach ($results as $key => $result) {
                $pom += $result;
            }
            $results['Other'] = $num-$pom;

        }



        return $results;
    }

    # save user visit
    public function saveUserVisit(Request $request) {
        $visit = \App\Visit::where('token', $request->token)->first();

        if (!$visit) {
            $visit = new \App\Visit();
            $visit->token = $request->token;
            $visit->save();
        }
    }

    # update user visit
    public function updateUserVisit(Request $request) {
        $visit = \App\Visit::where('token', $request->token)->first();
        if ($visit) {
            $visit->finished = 1;
            $visit->save();
        }
    }
}
