<div v-show="!showQuestions">
        <div class="container space shadow">       
                <div class="row">  
                    <div class="test okraj col-md-12 shadow"> 
                        <div class="row"> 
                            <div class="offset-md-1 col-md-11">  
                                <h1>Academic Integrity Self-Evaluation Tool for {{$whoName[0]->name}}s</h1>  
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
                            {{-- print part --}}
                                    <div id="printJS-form">
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
                                            Points received: <strong>@{{feedback[0].ptsFinal}} / @{{feedback[0].max}}</strong>
                                            <br><br>
                                            <div v-if="feedback[0].hasFeedback">
                                                {{-- @{{feedback}} --}}
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
                                                <div v-for="option in feedback">
                                                    {{-- @{{option}} --}}
                                                    <br>
                                                    <small>Line: @{{option.option}}
                                                    <span v-if="option.qtype == 2">
                                                        <br>Your choice: 
                                                        <span v-if="option.selected"> Selected</span><span v-else> Not selected</span>
                                                        <br>Points: 
                                                        <span v-if="option.value > option.value_not_checked">  {{-- if qvalue is ever greater than 1, then change it here --}}
                                                            <span v-if="option.selected">@{{option.value}} / @{{option.value}}</span>
                                                            <span v-else> 
                                                                <span v-if="option.value_not_checked !== null">@{{option.value_not_checked}}</span><span v-else>0</span> / @{{option.value}}
                                                            </span>
                                                        </span>
                                                        <span v-else>
                                                            <span v-if="option.selected">@{{option.value}} / @{{option.value_not_checked}}</span>
                                                            <span v-else> @{{option.value_not_checked}} / @{{option.value_not_checked}}</span>
                                                        </span>
                                                    </span> 
                                                    </small>
                                                </div>
                                            </div>
                                            <div v-else>

                                                    <div v-for="question in feedback">
                                                        {{-- question type 3 (rate 1-5) --}}
                                                        <div v-if="question.qtype == 3">
                                                            <span class="justify">Line: @{{question.option}}</span> 
                                                            <br> <small>Your choice: @{{question.value}}</small> 
                                                            <br> <small>Points received: @{{question.pts}} / @{{question.maxPtsPerLine}} </small>
                                                            <br>
                                                            <div class="greenish" v-html="question.feedback"></div>
                                                            <br>
                                                        </div>
        
                                                        {{-- other question types --}}
                                                        <div v-else>
                                                            <div> 
                                                                <small>Line: @{{question.option}}
                                                                    <span v-if="question.qtype == 2">
                                                                        <br>Your choice: 
                                                                        <span v-if="question.selected"> Selected</span><span v-else> Not selected</span>
                                                                        <br>Points: 
                                                                        <span v-if="question.value > question.value_not_checked">  {{-- if qvalue is ever greater than 1, then change it here --}}
                                                                            <span v-if="question.selected">@{{question.value}} / @{{question.value}}</span>
                                                                            <span v-else> 
                                                                                <span v-if="question.value_not_checked !== null">@{{question.value_not_checked}}</span><span v-else>0</span> / @{{question.value}}
                                                                            </span>
                                                                        </span>
                                                                        <span v-else>
                                                                            <span v-if="question.selected">@{{question.value}} / @{{question.value_not_checked}}</span>
                                                                            <span v-else> @{{question.value_not_checked}} / @{{question.value_not_checked}}</span>
                                                                        </span>
                                                                    </span> 
                                                                </small>
                                                                <br> 
                                                                <div v-if="question.feedback" class="greenish" v-html="question.feedback"></div> 
                                                                {{-- <div v-else>No feedback for this question.</div> --}}
                                                                <br v-if="question.qtype == 2">                                                              
                                                            </div>
                                                        </div>
                                                    </div>
                                            </div>
                                        </div>
                                    </div>
                                    
                                </div>{{-- div end print   --}}
                                <br> <br>
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
                                <div class="loader"></div>
                                <br>
                                <span v-if="showBottomBtns">
                                    <button type="button" class="btn btn-secondary" @click="continueWithForm()" v-if="showSection < {{count($sections)-1}}">Continue to next section</button>
                                    <button type="submit" class="btn btn-success darkblue" @click="submitForm()" v-else>Finish test</button>
                                </span>
                            </div>

                        </div>
                    </div>
                </div>
        </div>
</div>