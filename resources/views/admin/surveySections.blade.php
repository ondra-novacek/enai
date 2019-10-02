@foreach($sections as $section)
<div class="card">
    <div class="card-header">
        {{-- header --}}
        <button class="btn btn-link" data-toggle="collapse" data-target="#{{$section[0]->name}}" aria-expanded="true">
                <i class="fas fa-caret-right"></i> {{$section[0]->name}} 
        </button>
    </div>

    <div id="{{$section[0]->name}}" class="collapse show">
        {{-- body --}}
        <div class="card-body nopadding">
            @foreach($section as $question)

                {{-- Otazka 1 z n --}}
                @if ($question->qtype_id == 1)           
                    {{-- NADPIS --}}
                    <div class="lightbgc yespadding">
                        <div class="row">
                            <div class="col-md-8">{{$question->text}}</div>

                            <div class="col-md-4">    
                                <br>
                                <select class="form-control">
                                    <option><strong>Question 1 of X </strong></option>
                                </select>
                            </div>

                        </div>
                    </div>     

                    {{-- ODPOVEDI --}}
                    <ul>
                    @for ($i = 0; $i < count($options[$question->question_id]); $i++)
                    
                        {{-- CHANGE THIS PHP INTO JS FILE --}}
                        <?php $a =  $options[$question->id][$i]->value*$question->weight?>
                                
                       
                            <div class="yespadding">
                               <li> 
                                   {{$options[$question->id][$i]->name}}
                                    <i class="fas fa-times"></i>
                               </li>
                            </br></br>
                            </div>  
                       
                    @endfor
                    </ul>

                @endif

                {{-- Otazka n z m --}}
                @if($question->qtype_id == 2)            
                    {{-- NADPIS --}}
                    <div class="lightbgc yespadding">
                    <p><strong>Question X of Y: <br></strong>{{$question->text}}</p>
                    </div> 

                    @for ($i = 0; $i < count($options[$question->id]); $i++) 
                        <?php $a =  $options[$question->id][$i]->value*$question->weight?>
                                
                        <div class="yespadding">
                                {{$options[$question->id][$i]->name}}
                                <i class="fas fa-times"></i>
                            </br></br>
                        </div>  
                    @endfor
                @endif


            @endforeach
        </div>
    </div>
</div>
<br>
@endforeach