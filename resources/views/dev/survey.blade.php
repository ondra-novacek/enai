@extends('main')

@section('content')

@if (App::environment('local')) 
    <script src="https://unpkg.com/vue"></script>
@else
    <script src="https://cdn.jsdelivr.net/npm/vue@2.6.0"></script>
@endif

<script src="https://unpkg.com/axios/dist/axios.min.js"></script>

<div id="app" v-cloak>
  <a href="http://www.academicintegrity.eu"><img src="img/enai_sm.png" class="enai-two"></a>
    <div v-show="showQuestions">
        <form id="surForm" action="{{route('store')}}" method="post">
            {{-- id for who the survey is --}}
            <input type="hidden" name="who" value="{{$whoName[0]->id}}">
            <input type="hidden" name="totalMaxPts" v-bind:value="totalMaxPts">
            <input type="hidden" name="totalPts" v-bind:value="totalPts">

            @if (App::environment('local')) 
                <div style="display: none">@{{baseURL = ""}}</div>
            @else
                <div style="display: none">@{{baseURL = "/survey"}}</div>
            @endif

            {{-- INTRODUCTION PART --}}
            @include('dev.survey_parts.intro')
            
            {{-- DEMOGRAPHIC PART --}}
            @include('dev.survey_parts.demographic')
            
            {{-- SURVEY QUESTIONS HANDLER --}}
            @include('dev.survey_parts.questions') 

        </form>  
    </div>

    {{-- EVALUATION FOR SPECIFIC QUESTION PART --}}
    @include('dev.survey_parts.evaluation') 

    {{-- step by step indicators --}}
    <div class="" style="text-align:center;margin-top:40px;">
        @for ($i = -1; $i < (count($sections)); $i++)
        <span class="step" :class="{currstep: showSection == {{$i}}}"></span>
        @endfor
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
      totalPts: 0,
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
        addToPts(pts){
          this.totalPts += pts;
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
                  this.addToPts(this.feedbacks[this.feedbacks.length-1][1]);
                  //console.log('totalMaxPts so far:' + this.totalMaxPts);
                  if (this.feedbacks.length < 1) {this.loadingDataMsg = "No evaluation for those questions. Please continue to the next section."}
              })
              .catch(err => {console.log(err);this.loadingDataMsg = "Something went wrong and we could not fetch the feedbacks for this section. Please continue to the next section."});
            } catch (er){
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

