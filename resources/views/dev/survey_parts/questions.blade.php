@for ($x = 0; $x < count($sections); $x++)
    
    @if (!empty($sections[$x][0]))
        <div class="shadow" v-show="({{$x}} == showSection)">
            <div class="container space">       
               <div class="row">  
                  <div class="test okraj col-md-12"> 
                     <div class="row"> 
                           <div class="offset-md-1 col-md-11">  
                           <h1>Academic Integrity Self-Evaluation Tool for {{$whoName[0]->name}}s</h1>  
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
                                    <label for="skip {{$question->question_id}}"><input @click="toggleSkip('{{$question->subsection_id}} {{$question->question_id}}', {{$question->qtype_id}})" type="checkbox" value="skip" class="skipInputs" id="skip {{$question->question_id}}" name="skip {{$question->question_id}}" data-cust="{{$question->subsection_id}} {{$question->question_id}}">
                                        <i><small> Don't evaluate and skip this question.</small></i>
                                    </label>
                                    {{-- <p v-bind:class="{skipped: skippedQs.includes('{{$question->subsection_id}} {{$question->question_id}}')}">asdasd</p> --}}
                                    {{-- @{{skippedQs}} --}}
                                    @for ($i = 0; $i < count($options[$question->question_id]); $i++)
                                    
                                        <?php $a =  $options[$question->question_id][$i]->value*$question->weight?>
                                                
                                        <div class="radio">
                                        <label class="skip {{$question->question_id}}" v-bind:class="{skipped: skippedQs.includes('{{$question->subsection_id}} {{$question->question_id}}')}"><input type="radio" value="{{$a}}_{{$options[$question->question_id][$i]->id}}" id="{{$question->subsection_id}} {{$question->question_id}}" name="{{$question->subsection_id}} {{$question->question_id}}" :disabled="skippedQs.includes('{{$question->subsection_id}} {{$question->question_id}}')">
                                                {{$options[$question->question_id][$i]->name}}</label>
                                                
                                        </div>
                                    @endfor
                                    
                                @endif
        
                                
                                @if($question->qtype_id == 2)
                                    {{-- Otazka n z m --}}
                                    {{-- NADPIS --}}
                                    <p class="justify"><strong>@{{showSection+1}}.{{$index+1}} {{$question->text}}</strong></p>
                                    
                                    <label @click="toggleSkip('{{$question->subsection_id}} {{$question->question_id}}', {{$question->qtype_id}})" for="skip {{$question->question_id}}"><input type="checkbox" class="skipInputs" value="skip" id="skip {{$question->question_id}}" name="skip {{$question->question_id}} cb" data-cust="{{$question->subsection_id}} {{$question->question_id}}">
                                        <i><small @click="toggleSkip('{{$question->subsection_id}} {{$question->question_id}}', {{$question->qtype_id}})"> Don't evaluate and skip this question.</small></i>
                                    </label>

                                    @for ($i = 0; $i < count($options[$question->question_id]); $i++) 
                                        <?php $a =  $options[$question->question_id][$i]->value*$question->weight?>
                                                
                                        <div class="checkbox">
                                            <label class="skip {{$question->question_id}}" v-bind:class="{skipped: skippedQs.includes('{{$question->subsection_id}} {{$question->question_id}}')}"><input type="checkbox" class="skip {{$question->question_id}}" value="{{$a}}_{{$options[$question->question_id][$i]->id}}" name="{{$question->subsection_id}} {{$question->question_id}} {{$i}}" :disabled="skippedQs.includes('{{$question->subsection_id}} {{$question->question_id}}')">
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
                                    <label for="skip {{$question->question_id}}"><input @click="toggleSkip('{{$question->subsection_id}} {{$question->question_id}}', {{$question->qtype_id}})" type="checkbox" value="skip" class="skipInputs" id="skip {{$question->question_id}}" name="skip {{$question->question_id}} count {{count($options[$question->question_id])}}" data-cust="{{$question->subsection_id}} {{$question->question_id}}">
                                        <i><small> Don't evaluate and skip this question.</small></i>
                                    </label>
                                    <table class="table table-hover">
                                        <thead class="thead-light">
                                            <tr>
                                                <td scope="col"></td>
                                                @if (count($options[$question->question_id]) > 0) 
                                                    @for ($i = 1; $i <= $options[$question->question_id][0]->question->rateTo;$i++)
                                                        @if ($options[$question->question_id][0]->question->rateTo <= sizeof($question->question_column))
                                                            <td scope="col" v-bind:class="{skipped: skippedQs.includes('{{$question->subsection_id}} {{$question->question_id}}')}">{{$question->question_column[$i-1]->name}}</td>
                                                        @else
                                                            <td scope="col" v-bind:class="{skipped: skippedQs.includes('{{$question->subsection_id}} {{$question->question_id}}')}">{{$i}}</td>
                                                        @endif
                                                    @endfor 
                                                @endif
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @for ($i = 0; $i < count($options[$question->question_id]); $i++)
                                            {{-- PHP dznamic counting of value?? --}}
                                            <tr>
                                                <td scope="row" v-bind:class="{skipped: skippedQs.includes('{{$question->subsection_id}} {{$question->question_id}}')}">{{$options[$question->question_id][$i]->name}}</td>
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
                                                        } 
                                                        // else {
                                                        //     $optvalue = $j;
                                                        //     $optvalue = 0;
                                                        // }
                                                        // echo $optvalue;
                                                    ?>

                                                    <td class="tdbuttons vertical-inputcircle"><label><input type="radio" name="{{$question->subsection_id}} {{$question->question_id}} {{$i}}" value="{{$optvalue*$question->weight*$options[$question->question_id][$i]->value}}_{{$options[$question->question_id][$i]->id}}_{{$j}}" :disabled="skippedQs.includes('{{$question->subsection_id}} {{$question->question_id}}')"></label></td>
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
                                    <button type="button" class="btn btn-secondary saveform" @click="nextPage(1)">Evaluate section</button>
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