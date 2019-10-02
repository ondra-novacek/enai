<?php

namespace App\Http\Controllers;

use App\Subsection;
use App\Section_Score;
use App\Test_Score;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
// use Symfony\Component\HttpFoundation\Request;

class ApiEvaluationsController extends Controller
{
    public function getEvaluations($qforId){
        $evals = [];
        $sections = Subsection::orderBy('survey_subsections.order')->get();
        foreach ($sections as $section) {
            $evals[] = DB::table('survey_subsections')
                ->join('survey_section_scores', 'survey_section_scores.subsection_id', '=', 'survey_subsections.id')
                ->where('survey_section_scores.subsection_id', $section->id)
                ->where('survey_section_scores.qfor_id', $qforId)
                ->get();
        }

        return $evals;        
    }

    public function deleteEvals(Request $request){
        $ids = $request->ids;

        if ($ids) {
            foreach ($ids as $id) {
                Section_score::destroy($id);
            }
        }  
        
        // return $ids;
    }

    public function addEvaluation(Request $request){
        $eval = new Section_Score();

        $eval->text = $request->text;
        $eval->min_pts = $request->pts;
        $eval->subsection_id = $request->section_id;
        $eval->qfor_id = $request->idwho;

        $eval->save();
    }

    public function getEvaluationsFinal($id){
        $evals = DB::table('survey_test_scores')
                ->where('qfor_id', $id)
                ->get();

        return $evals;        
    }

    public function addEvaluationFinal(Request $request){
        $eval = new Test_Score();

        $eval->text = $request->text;
        $eval->min_pts = $request->pts;
        $eval->stars = $request->stars;
        $eval->qfor_id = $request->qfor_id;

        $eval->save();
    }

    public function deleteEvalFinal(Request $request){
        Test_score::destroy($request->id);
    }
}
