@extends('main')

@section('content')
<img src="img/enai_sm.png" class="enai">
<div class="container space shadow">       
        <div class="row">  
           <div class="test okraj col-md-12 shadow"> 
              <div class="row"> 
                    <div class="offset-md-1 col-md-11">  
                    <h1>Academy ethics: survey for students</h1>  
                    <br>
                    @isset($texts)
                    <strong>Acknowledgement</strong>
                    <p>We are most grateful for your contribution to this tool.</p>
                    <p>If you wish to provide feedback or contact the organizers of this tool, please send an email to inga.gaizauskaite@lstc.lt
                    </p>
                    {{-- EVALUATION FOR THE WHOLE TEST --}}
                    @isset ($finaltext[0]) 
                            <strong>Total score: {{$totalresult}}</strong>
                            <p class="greenish">{{$finaltext[0]->text}}</p>
                            @for($i = 0; $i < $finaltext[0]->stars; $i++)
                                <i class="fas fa-star"></i>
                            @endfor
                        @endisset
                        <br>
                        
                    {{-- EVALUATION FOR SECTIONS     --}}
                    @for ($i = 0; $i < count($sections); $i++)
                        @if($feedbacks[$i] || $texts[$i])
                        <hr>
                            <h4>Section: <strong>{{$sections[$i]->name}}</strong></h4> <br>

                            @foreach ($feedbacks[$i] as $q)
                            <div class="vl qfeedback">
                                <strong>Question: {{$q[0]->question}}</strong><br>
                                @foreach ($q as $feedback)
                                    @if($feedback->qtype_id == 3)
                                        <br>
                                        Line: {{$feedback->selectedOption}} (your choice: {{$feedback->value}}) <br>
                                        <div class="greenish">{{$feedback->feedback}}</div>
                                    @else
                                        <small>Your choice: {{$feedback->selectedOption}}</small><br>
                                        <div class="greenish">{{$feedback->feedback}}</div>  
                                    @endif
                                @endforeach
                            </div> <br>
                            @endforeach

                            @if(count($texts[$i]) != 0)
                            <p>
                                <div class="greenish">{{$texts[$i][0]->text}}</div><br>
                            @else
                            <div class="greenish">No evaluation for this section has been made</div><br>
                            </p>
                            @endif
                        @endif
                        
                    @endfor
                    <a href="/">Send another survey</a>    
                    @else
                    <script type="text/javascript">
                        window.location = "{{ url('/') }}";//here double curly bracket
                    </script>
                    <p>Session expired.</p>
                    @endisset
                    </div>
              </div>
           </div>
        </div>
</div>
@stop