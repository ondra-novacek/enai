<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});
#CREATE SURVEYS
Route::get('/createsurveys', 'ApiController@createSurveys');

## GET ALL
Route::get('/surveydata/{id}', 'ApiController@DownloadAll');

## GET NAME OF THE TARGET GROUP
Route::get('/whoname/{id}', 'ApiController@SurveyFor');

## GET SECTIONS OF A SURVEY
Route::get('/sections/{id}', 'ApiController@Sections');

## GET Options
Route::get('/options/{id}', 'ApiController@Options');

## GET Survey's names
Route::get('/surveys', 'ApiController@Surveys');

## DELETE option by id
Route::get('/deleteoption/{id}', 'ApiController@DeleteOption');

## GET QUESTIONS FOR DEMOGRAPHICS SECTION FOR A CERTAIN SURVEY
Route::get('/getDemographicQuestions/{id}', 'ApiController@getDemographicQuestions');

## ADD suboption
Route::post('/addsuboption', 'ApiController@addSuboption');

## EDIT SUBOPTION FEEDBACK
Route::post('/editsuboptionfeedback', 'ApiController@editSuboption');

## EDIT SUBOPTION VALUE
Route::post('/editsuboptionvalue', 'ApiController@editSuboptionValue');

## EDIT OR ADD COLUMN IN QUESTION TYPE 3
Route::post('/editcolumnvalue', 'ApiController@editColumnValue');

## EDIT ROW TEXT
Route::post('/editrowtext', 'ApiController@editRowText');

## UPDATE NOTE
Route::post('/updatenote', 'ApiController@updateNote');

## DELETE OPTION ROW
Route::post('/deleteoptionrow', 'ApiController@deleteOptionRow');

## ADD option
Route::post('/addoption', 'ApiController@AddOption');

## ADD one feedback question
Route::post('/addOneFeedback', 'ApiController@addOneFeedback');

## EDIT option
Route::post('/editoption', 'ApiController@EditOption');

## EDIT cap PTS
Route::post('/updatecappts', 'ApiController@updateCapPts');

## EDIT feedback option
Route::post('/editoptionfeedback', 'ApiController@EditOptionFeedback');

## EDIT feedback option when NOT selected
Route::post('/editoptionfeedbacknotsel', 'ApiController@EditOptionFeedbackNotSel');

## ADD NEW QUESTION
Route::post('/addnewquestion', 'ApiController@AddNewQuestion');

## ADD EXISTING QUESTION TO A SURVEY
Route::post('/addexistingquestion', 'ApiController@AddExistingQuestion');

## QUESTION TEXT EDIT
Route::post('/editquestiontext', 'ApiController@EditQuestionText');

## QUESTION VALUE EDIT
Route::post('/editquestionvalue', 'ApiController@EditQuestionValue');

## QUESTION RATE TO EDIT
Route::post('/editquestionrateto', 'ApiController@EditQuestionRateTo');

## QUESTION TYPE EDIT
Route::post('/editquestiontype', 'ApiController@EditQuestionType');

## QTYPE FETCH ALL
Route::get('/qtypefetch', 'ApiController@QtypeFetch');

## SUBSECTIONS FETCH ALL
Route::get('/subsectionsfetch', 'ApiController@SubsectionsFetch');

## OTHER QUESTIONS FETCH
Route::get('/otherquestionsfetch', 'ApiController@otherQuestionsFetch');

## GET SURVEY DESCRIPTION
Route::get('/getDescription/{id}', 'ApiController@getDescription');

## CHANGE SURVEY DESCRIPTION
Route::post('/editsurveydesc', 'ApiController@editSurveyDesc');

## ADD NEW SECTION
Route::post('/addnewsection', 'ApiController@addNewSection');

## CHANGE SECTION IN QUESTION
Route::post('/changequestionsection', 'ApiController@changeQuestionSection');

## DELETE QUESTION, SEE MORE IN FUNCTION
Route::post('/deletequestion', 'ApiController@deleteQuestion');

## EDIT SECTION
Route::post('/editsection', 'ApiController@editSection');

## DELETE SECTION
Route::post('/deletesection', 'ApiController@deleteSection');

## GET RESULTS
Route::get('/getresults', 'ApiResultsController@getAllResults');

## EDIT EVALUALTION FOR A SECTION
Route::post('/editeval', 'ApiController@editEval');

## EDIT EVALUATION FOR THE WHOLE TEST
Route::post('/editevalfinal', 'ApiController@editEvalFinal');

## GET PEOPLE
Route::get('/getpeople', 'ApiResultsController@getAllPeople');

## SWAP SECTION ORDER
Route::post('/swapSectionOrder', 'ApiController@swapSectionOrder');

## GET USER'S ANSWERS, SAVE THEM AND SEND FEEDBACKS BACK
Route::post('/evaluateOneSection', 'ApiController@evaluateOneSection');

########### EVALUATIONS ##############
## GET SECTION SCORE RESPONSES WITH SECTION NAMES
Route::get('/getevaluations/{id}', 'ApiEvaluationsController@getEvaluations');

## DELETE EVALUATIONS
Route::post('/deleteevals', 'ApiEvaluationsController@deleteEvals');

## ADD EVALUATION
Route::post('/addevaluation', 'ApiEvaluationsController@addEvaluation');

########### FINAL EVALUATIONS ##############
## GET EVALS
Route::get('/getevaluationsfinal/{id}', 'ApiEvaluationsController@getEvaluationsFinal');

## ADD EVAL
Route::post('/addevaluationfinal', 'ApiEvaluationsController@addEvaluationFinal');

## DELETE FINAL EVAL
Route::post('/deleteeval', 'ApiEvaluationsController@deleteEvalFinal');

############ RESULTS ############
Route::delete('/deleteallresults', 'ApiController@deleteAllResults');

Route::get('/getSubmittedDates', 'ApiController@getSubmittedDates');

Route::get('/getRespondentsByCountry', 'ApiController@getRespondentsByCountry');