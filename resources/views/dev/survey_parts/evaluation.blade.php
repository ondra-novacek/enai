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
                                                            <div v-if="question.feedback">
                                                                <small>Line: @{{question.option}}
                                                                    <span v-if="question.qtype == 2">
                                                                        <span v-if="question.selected"> (selected)</span><span v-else> (not selected)</span>
                                                                    </span> 
                                                                </small>
                                                                <br> 
                                                                <div class="greenish">@{{question.feedback}}</div>
                                                                <br> 
                                                            </div>
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
                                <div class="loader"></div>
                                <button type="button" class="btn btn-secondary" @click="continueWithForm()" v-if="showSection < {{count($sections)-1}}">Continue to next section</button>
                                <button type="submit" class="btn btn-success darkblue" @click="submitForm()" v-else>Finish test</button>
                            </div>

                        </div>
                    </div>
                </div>
        </div>
</div>