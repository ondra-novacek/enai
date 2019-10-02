@extends('main')

@section('content')

{{-- dev --}}
<script src="https://unpkg.com/vue"></script>
{{-- production --}}
{{-- <script src="https://cdn.jsdelivr.net/npm/vue@2.6.0"></script> --}}
<script src="https://unpkg.com/axios/dist/axios.min.js"></script>

<div id="app" v-cloak>
{{-- {{dd($sections)}} --}}
  <a href="http://www.academicintegrity.eu"><img src="img/enai_sm.png" class="enai-two"></a>
<div v-show="showQuestions">
  <form id="surForm" action="{{route('store')}}" method="post">
    {{-- id for who the survey is --}}
    <input type="hidden" name="who" value="{{$whoName[0]->id}}">
    <input type="hidden" name="totalMaxPts" v-bind:value="totalMaxPts">

    @if (App::environment('local')) 
        <div style="display: none">@{{baseURL = ""}}</div>
    @else
        <div style="display: none">@{{baseURL = "/survey"}}</div>
    @endif

    <div v-show="showSection == -2">
            <div class="container space shadowcopy">       
                    <div class="row">  
                    <div class="test okraj col-md-12"> 
                        <div class="row"> 
                                <div class="offset-md-1 col-md-11">  
                                <h1>Academic Integrity: Survey for {{$whoName[0]->name}}</h1>  
                                <br>
                                </div>

                                
                                    <div class="podnadpis col-md-12">  
                                        <div  class="offset-md-1 col-md-11" style="padding: 0;">
                                        <h3>Introduction</h3>
                                        </div>
                                    </div>
                
                <div class="offset-md-1 col-md-10">
                <br>    
                {!!$intro[0]->description!!}
                <br><br>
                <button class="btn btn-secondary" type="button" @click="nextPage(-1)">Continue</button>
            </div></div></div></div></div>    

    
    
    </div>
    {{-- START OF DEMO PART --}}
    <div v-show="showSection == -1" id="demo">
            <div class="container space shadowcopy">       
                    <div class="row">  
                    <div class="test okraj col-md-12"> 
                        <div class="row"> 
                                <div class="offset-md-1 col-md-11">  
                                <h1>Academic Integrity: Survey for {{$whoName[0]->name}}</h1>  
                                <br>
                                </div>

                                
                                    <div class="podnadpis col-md-12">  
                                        <div class="offset-md-1 col-md-11 no-left-padding" style="padding: 0;">
                                        <h3>Demography</h3>
                                        </div>
                                    </div>
                
        <div class="offset-md-1 col-md-10">
            
            <br>
            <label for="country"><strong>Country:</strong></label>
            <select class="custom-select" name='country' id='country'>
                <option v-for="country in countries" v-bind:value='country'>@{{country}}</option>
            </select>
            <br><br>     
            <label for="gender"><strong>Gender:</strong></label>
                <select class="custom-select" name='gender' id='gender'>
                    <option value='male'>male</option>
                    <option value='female'>female</option>
                </select>
            <br><br> 
            <label for="age"><strong>Age:</strong></label>
                <input type="number" name="age" class="form-control" id="age" min="15" step="1">
            <br>  

        @foreach ($demoquestions as $question)
        {{-- qtype = 5 rollup quesiton --}}
        @if ($question->qtype_id == 5)
            <label for="{{$question->id}}"><strong>{{$question->text}}</strong></label>
            <select class="custom-select" name="demo_{{$question->id}}" id="{{$question->text}}">
                @foreach ($question->option as $option)
                    <option value="_{{$option->id}}">{{$option->name}}</option> 
                @endforeach
            </select>
        @endif

        {{-- qtype = 4 plain text quesiton --}}
        @if ($question->qtype_id == 4)
            <label for="{{$question->id}}"><strong>{{$question->text}}</strong></label>
            <input class="form-control" type="text" name="demo_{{$question->id}}">
        @endif
        
            <br><br>
        @endforeach

        <div class="form-check">
            <input class="form-check-input" type="checkbox" value="1" name="agreement" id="agreement">
            <label class="form-check-label" for="agreement">
                   <small>I am giving my consent to the processing of personal data to 
                    have surveys and marketing, statistical and other research carried out.</small>
            </label>
        </div>
        <br>
        <br>
        <div class="alert alert-info" role="alert" v-if="msg" v-text="msg">
        </div> 
        <div class="alert alert-info" role="alert" v-if="err" v-text="err">
        </div>

        <button class="btn btn-secondary" type="button" id="prevBtn" @click="nextPage(0)">Next</button>
    </div></div></div></div></div>    
    </div>{{-- end of div onepart --}}
    {{-- END DEMO PART --}}
    @for ($x = 0; $x < count($sections); $x++)
    
    @if (!empty($sections[$x][0]))
        <div class="shadow" v-show="({{$x}} == showSection)">
            <div class="container space">       
               <div class="row">  
                  <div class="test okraj col-md-12"> 
                     <div class="row"> 
                           <div class="offset-md-1 col-md-11">  
                           <h1>Academic Integrity: Survey for {{$whoName[0]->name}}</h1>  
                           <br>
                           </div>
                           {{-- SECTION HEADER --}}
                            <div class="podnadpis col-md-12">  
                                <div class="offset-md-1 col-md-11" style="padding: 0;">
                                <h3>@{{showSection+1}}. {{$sections[$x][0]->name}}</h3>
                                </div>
                            </div>
                            {{-- QUESTIONS PART --}}
                           <div class="offset-md-1 col-md-10">
                            
                                @foreach ($sections[$x] as $index=>$question)
                                <br>
                                @if ($question->qtype_id == 1) 
                                    {{-- Otazka 1 z n --}}
                                    {{-- NADPIS --}}
                                    <p class="justify"><strong>@{{showSection+1}}.{{$index+1}} {{$question->text}}</strong></p>
                                    {{-- {{dd($question)}} --}}
                                    @for ($i = 0; $i < count($options[$question->question_id]); $i++)
                                    
                                        <?php $a =  $options[$question->question_id][$i]->value*$question->weight?>
                                                
                                        <div class="radio">
        
                                        <label><input type="radio" value="{{$a}}_{{$options[$question->question_id][$i]->id}}" id="{{$question->subsection_id}} {{$question->question_id}}" name="{{$question->subsection_id}} {{$question->question_id}}">
                                                {{$options[$question->question_id][$i]->name}}</label>
                                                
                                        </div>
                                    @endfor
                                @endif
        
                                
                                @if($question->qtype_id == 2)
                                    {{-- Otazka n z m --}}
                                    {{-- NADPIS --}}
                                    <p class="justify"><strong>@{{showSection+1}}.{{$index+1}} {{$question->text}}</strong></p>
                                    @for ($i = 0; $i < count($options[$question->question_id]); $i++) 
                                        <?php $a =  $options[$question->question_id][$i]->value*$question->weight?>
                                                
                                        <div class="checkbox">
                                            <label><input type="checkbox" value="{{$a}}_{{$options[$question->question_id][$i]->id}}" name="{{$question->subsection_id}} {{$question->question_id}} {{$i}}">
                                                {{$options[$question->question_id][$i]->name}}</label>
                                        </div>
                                    @endfor
                                    @if($question->note)
                                    <small>{{$question->note}}</small>
                                    <br>
                                    @endif
                                @endif
                                    
                                @if($question->qtype_id == 3)
                                    {{-- Question rate 1-5 --}}
                                    <p class="justify"><strong>@{{showSection+1}}.{{$index+1}} {{$question->text}}</strong></p>
                                    <br>
                                    <table class="table table-hover">
                                        <thead class="thead-light">
                                            <tr>
                                                <td scope="col"></td>
                                                @if (count($options[$question->question_id]) > 0) 
                                                    @for ($i = 1; $i <= $options[$question->question_id][0]->question->rateTo;$i++)
                                                        @if ($options[$question->question_id][0]->question->rateTo <= sizeof($question->question_column))
                                                            <td scope="col">{{$question->question_column[$i-1]->name}}</td>
                                                        @else
                                                            <td scope="col">{{$i}}</td>
                                                        @endif
                                                    @endfor 
                                                @endif
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @for ($i = 0; $i < count($options[$question->question_id]); $i++)
                                            {{-- PHP dznamic counting of value?? --}}
                                            <tr>
                                                <td scope="row">{{$options[$question->question_id][$i]->name}}</td>
                                                {{-- {{$options[$question->question_id][0]->question->rateTo}} --}}
                                                @for ($j = 1; $j <= $options[$question->question_id][0]->question->rateTo; $j++)
                                                    {{-- value of answer is question value * option value * $optvalue --}} {{-- $j at the end is kept, so we know, which radioinput was selected --}}
                                                    <?php 
                                                        if (sizeof($options[$question->question_id][$i]->suboption) > 0){
                                                            try{
                                                                $optvalue = $options[$question->question_id][$i]->suboption[$j-1]->value;
                                                            }catch(Exception $e){
                                                                $optvalue = 0;
                                                            }
                                                        } else {
                                                            $optvalue = $j;
                                                        }
                                                        // echo $optvalue;
                                                    ?>
                                                    
                                                    <td class="tdbuttons vertical-inputcircle"><label><input type="radio" name="{{$question->subsection_id}} {{$question->question_id}} {{$i}}" value="{{$optvalue*$question->weight*$options[$question->question_id][$i]->value}}_{{$options[$question->question_id][$i]->id}}_{{$j}}"></label></td>
                                                @endfor
                                            </tr>
                                            @endfor
                                        </tbody>
                                    </table>
                                @endif 
                                
                                @endforeach 
        
                            <input type="hidden" name="_token" value="{{ csrf_token() }}"> 
                            
                            <br>
                            <div class="alert alert-info" role="alert" v-if="msg" v-text="msg">
                            </div>           

                            <div style="overflow:auto;">  
                                <div style="float:left;">
                                    {{-- <button class="btn btn-secondary" type="button" id="prevBtn" @click="nextPage(-1)" v-show="showSection >= 0">Previous</button> --}}
                                    {{-- <button class="btn btn-secondary" type="button" id="nextBtn" @click="nextPage(1)" v-if="showSection < {{count($sections)-1}}">Next</button> --}}
                                    {{-- <input value="Submit form" class="btn btn-success" @click="submitForm" v-else> --}}
                                    <button type="button" class="btn btn-secondary" @click="nextPage(1)">Evaluate section</button>
                                </div>
                            </div>        

                           </div>        
                        </div>
                    </div>
                </div>
            </div>
        </div>               
    @endif
    @endfor 

    {{-- step by step indicators --}}
    <div class="" style="text-align:center;margin-top:40px;">
        @for ($i = -1; $i < (count($sections)); $i++)
        <span class="step" :class="{currstep: showSection == {{$i}}}"></span>
        @endfor
    </div>

  </form>  
  
</div>

{{-- EVALUATION FOR SPECIFIC QUESTION PART --}}
<div v-show="!showQuestions">
        <div class="container space shadow">       
                <div class="row">  
                    <div class="test okraj col-md-12 shadow"> 
                        <div class="row"> 

                            <div class="offset-md-1 col-md-11">  
                                <h1>Academic Integrity: Evaluation for a section</h1>  
                                <br>
                            </div>
                            {{-- SECTION HEADER --}}
                            <div class="podnadpis col-md-12">  
                                <div class="offset-md-1 col-md-11" style="padding: 0;">
                                <h3 v-if="typeof feedbacks[0] !== 'undefined'" v-text="(showSection + 1) + '. ' + feedbacks[0][0].subsection"></h3>
                                </div>
                            </div>
{{-- @{{feedbacks}} --}}     
                            <div class="offset-md-1 col-md-11" v-if="feedbacks.length > 0"> 
                            
                            
                            {{-- WHOLE SECTION FEEDBACK PART--}}
                                    <div>
                                      <br>
                                      Points: <strong>@{{feedbacks[feedbacks.length-1][1]}} / @{{feedbacks[feedbacks.length-1][2]}}</strong>
                                      <br><br>
                                      <div v-if="typeof feedbacks[feedbacks.length-1][0][0] !== 'undefined'">
                                            <div class="sectfeedback" v-html="feedbacks[feedbacks.length-1][0][0].text"></div><br>
                                        </div>
                                        <div v-else>No evaluation for the whole section.</div>
                                    </div>
                            
                                    <br> 
                                    <div v-for="(feedback, index) in feedbacks">
                                        <div class="vl qfeedback justify" v-if="feedback.length > 0 && index != feedbacks.length-1">
                                            <strong>@{{(showSection + 1)}}.@{{index+1}} Question: @{{feedback[0].question}}</strong><br>
                                            Points recieved: <strong>@{{feedback[0].ptsFinal}}/@{{feedback[0].max}}</strong>
                                            <br><br>
                                            <div v-if="feedback[0].hasFeedback">
                                                <div v-if="feedback[0].splitPts > feedback[0].ptsFinal">
                                                        <div class="greenish">@{{feedback[0].originalFB}}</div>
                                                        {{-- <br> --}}
                                                    {{-- Final: @{{feedback[0].ptsFinal}} <br>
                                                    Split: @{{feedback[0].splitPts}} <br> --}}
                                                </div>
                                                <div v-else>
                                                    <div class="greenish">@{{feedback[0].altFB}}</div>
                                                    {{-- <br> --}}
                                                    {{-- Final: @{{feedback[0].ptsFinal}} <br>
                                                    Split: @{{feedback[0].splitPts}} <br> --}}
                                                </div>
                                            </div>
                                            <div v-else>

                                                <div v-for="question in feedback">
                                                    {{-- question type 3 (rate 1-5) --}}
                                                    <div v-if="question.qtype == 3">
                                                        <span class="justify">Line: @{{question.option}}</span> <br> <small>Your choice: @{{question.value}}</small> <br> 
                                                        <div class="greenish">@{{question.feedback}}</div>
                                                        <br>
                                                    </div>
    
                                                    {{-- other question types --}}
                                                    <div v-else>
                                                        <small>Your choice: @{{question.option}}</small> <br> 
                                                        <div class="greenish">@{{question.feedback}}</div>
                                                        <br> 
                                                    </div>
                                                </div>

                                            </div>

                                            
                                        </div>
                                        <br>
                                    </div>
                                    
                                    

                                    <div style="overflow:auto;">  
                                        <div style="float:left;">
                                            <button type="button" class="btn btn-secondary" @click="continueWithForm()" v-if="showSection < {{count($sections)-1}}">Continue to next section</button>
                                            <button type="submit" class="btn btn-success darkblue" @click="submitForm()" v-else>Finish test</button>
                                        </div>
                                    </div>               
                            </div>

                            <div class="offset-md-1 col-md-11" v-else>
                                <br>
                                <p>@{{loadingDataMsg}}</p>
                                <button type="button" class="btn btn-secondary" @click="continueWithForm()" v-if="showSection < {{count($sections)-1}}">Continue to next section</button>
                                <button type="submit" class="btn btn-success darkblue" @click="submitForm()" v-else>Finish test</button>
                            </div>

                        </div>
                    </div>
                </div>
        </div>
</div> 


</div>

<script>
new Vue({
    el: '#app',
    data: {
      who: '',
      questions: '',
      options: '',
      showSection: -2,
      msg: '',
      feedbacks: [],
      showQuestions: true,
      loadingDataMsg: 'Loading...',
      age: '',
      countries: [],
      err: '',
      totalMaxPts: 0,
      baseURL: '/survey'
    },
    methods: {
        getData(){
            axios.get(this.baseURL + '/api/surveydata/' + 1)
            .then((response)=>{
                this.who = response.data[0];
                this.questions = response.data[1];
                this.options = response.data[2];
            })
            .catch((err)=>{console.log(err)});
        },
        getCountries(){
            axios.get('https://restcountries.eu/rest/v2/all')
            .then(response => {
                this.countries = response.data.map(country => country.name);
                // console.log(this.countries);
            })
            .catch(err => {console.log(err)});
        },
        addToTotalPts(pts){
          this.totalMaxPts += pts;
        },
        nextPage(direction){
            this.msg = "";
            let unanswered;
            // next page
            if (direction === 1) {
                unanswered = this.InputsValidator();
                if (unanswered == 0){
                    // this.showSection += 1;
                    this.evaluateSection();
                } else if (unanswered == 1) {
                    this.feedbacks = '';
                    this.msg = unanswered + ' question has not been answered.';
                } else {
                    this.feedbacks = '';
                    this.msg = unanswered + ' questions have not been answered.';
                }
            // prev page
            } else if(direction === -1){
                this.showSection += 1;
                scroll(0,0);
            // next page from demographic section
            } else {
                let unanswered = this.DemoInputsValidator();
                let agreementChecked = document.getElementById('agreement').checked;
                // console.log(agreement)

                if ( ((this.err === '') && (unanswered == 0)) || (!agreementChecked)){
                    this.msg = '';
                    this.showSection += 1;
                    scroll(0, 0);
                } else if (unanswered == 1) {
                    this.msg = unanswered + ' question has not been answered.';
                } else if (unanswered > 1) {
                    this.msg = unanswered + ' questions have not been answered.';
                }
            }   
        },
        DemoInputsValidator(){
            this.err = '';
            let demo = document.getElementById('demo');
            let demoInputs = demo.getElementsByTagName('input');
            let age = demoInputs.age.value;
            if (age < 15) {
              this.err = "Age must be 15 or higher.";
            }
            let unanswered = 0;
            for (let i = 0; i < demoInputs.length; i++) {
                if (demoInputs[i].value == '') {
                    unanswered += 1;
                }
            };
            return unanswered;
        },
        InputsValidator(){
            var x = document.getElementsByClassName("shadow");
            var AllOnePartElements = x[this.showSection].getElementsByTagName("input");
            // return AllOnePartElements;

            var ShowAlert = '';
            var notAnswered = 0;
            let answeredQs = [];
            let qsNotChecked = [];
            let qsChecked = [];
            let pom;

            for (i = 0; i < AllOnePartElements.length; i++)
            {
                
                if (AllOnePartElements[i].type == 'radio')
                {
                    var ThisRadio = AllOnePartElements[i].name;
                    //console.log('Radio: ', ThisRadio);
                    //console.log('All el: ', AllOnePartElements[i]);
                    var ThisChecked = 'No';
                    var AllRadioOptions = document.getElementsByName(ThisRadio);
                    // console.log(AllRadioOptions);
                    let id = '';
                    for (j = 0; j < AllRadioOptions.length; j++)
                    {
                        if (AllRadioOptions[j].checked && ThisChecked == 'No')
                        {
                            ThisChecked = 'Yes';
                            let pomid = AllRadioOptions[j].value;
                            pomid = pomid.split("_");
                            if(typeof pomid[2] === 'undefined') {
                                id = pomid[1];
                                break;
                            } else {
                                id = pomid[1] + '_' + pomid[2];
                                break;
                            }
                        } 
                    }
                    id ? answeredQs.push(id) : null;
                    var AlreadySearched = ShowAlert.indexOf(ThisRadio);
                    if (ThisChecked == 'No' && AlreadySearched == -1)
                    {
                        notAnswered++;
                        ShowAlert = ShowAlert + ThisRadio;
                    }  
                } else if (AllOnePartElements[i].type == 'checkbox' ) {
                    pom = AllOnePartElements[i].value.split("_");
                    pom2 = AllOnePartElements[i].name.split(" ");
                    if (AllOnePartElements[i].checked) {
                        //get answered options from checkbox questions
                        qsChecked.push(pom2[1]); 	
                        answeredQs.push(pom[1]);
                    } else {
                        //option was not selected, but we need to keep it in order to give feedback on unchecked options
                        qsNotChecked.push(pom2[1]);
                       
                    }
                }
            }
            //clear it from duplicities
            answeredQs = answeredQs.filter((v, i, a) => a.indexOf(v) === i);
            qsChecked = qsChecked.filter((v, i, a) => a.indexOf(v) === i);
            qsNotChecked = qsNotChecked.filter((v, i, a) => a.indexOf(v) === i);
            //notchecked question type 2 (checkbox)
            var notCheckedQs = qsNotChecked.filter(function(obj) { return qsChecked.indexOf(obj) == -1; });

            // console.log(notCheckedQs);
            if (notAnswered === 0){
                this.getFeedbacks(answeredQs, notCheckedQs);
            }
            return notAnswered;
        },
        submitForm(){
            // let notSelected = this.InputsValidator();
            // console.log(notSelected + 'have not been s.')
            // if (notSelected > 1) {
            //     this.msg = notSelected + " questions have not been selected.";
            //     return 0;
            // } else if (notSelected === 1) {
            //     this.msg = notSelected + " question has not been selected.";
            //     return 0;
            // }
            document.getElementById("surForm").submit();
        },
        getFeedbacks(answers, notChecked){
            this.loadingDataMsg = "Loading...";  
            //console.log(answers);
            //console.log(notChecked);
            try{
              axios.post(this.baseURL + '/api/evaluateOneSection', {selectedOptions: answers, forgottenQs: notChecked, who: {{$whoName[0]->id}} })
                .then(response => {
                  console.table(response.data);
                  this.feedbacks = response.data;
                  //console.log('Response data: ', this.feedbacks);
                  // this.feedbacks = [];
                  //add to total pts
                  this.addToTotalPts(this.feedbacks[this.feedbacks.length-1][2]);
                  //console.log('totalMaxPts so far:' + this.totalMaxPts);
                  if (this.feedbacks.length < 1) {this.loadingDataMsg = "No evaluation for those questions. Please continue to the next section."}
              })
              .catch(err => {console.log(err);this.loadingDataMsg = "Something went wrong and we could not fetch the feedbacks for this section. Please continue to the next section."});
            } catch {
                  //nothing here
            }
            
        },
        evaluateSection(){
            scroll(0, 0);
            this.showQuestions = false;
        },
        continueWithForm(){
            scroll(0, 0);
            this.feedbacks = '';
            this.showQuestions = true;
            this.showSection += 1;
        }
    },
    mounted(){
        // this.getData();\
        this.getCountries();
    }
  })

</script>

@endsection

<style>
[v-cloak] {
    display: none;
}

.currstep {
    opacity: 1;
  }

  .tdbuttons {
    text-align: center;
    vertical-align: middle;
  }

  .darkblue{
      background-color: rgb(0, 153, 153) !important;
  }
</style>

