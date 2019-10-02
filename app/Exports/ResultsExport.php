<?php

namespace App\Exports;

use App\Result_Question;
//use Maatwebsite\Excel\Concerns\FromCollection;

class ResultsExport implements FromCollection
{
    public function collection()
    {
        return Result_Question::join('survey_questions', 'question_id', '=', 'survey_questions.id')
                    ->get();
    }
}