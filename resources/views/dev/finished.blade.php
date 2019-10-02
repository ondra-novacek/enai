@extends('main')

@section('content')
<img src="img/enai_sm.png" class="enai">
<div class="container space shadow">       
        <div class="row">  
           <div class="test okraj col-md-12 shadow"> 
              <div class="row"> 
                    <div class="offset-md-1 col-md-11">  
                    <h1>Academy Integrity: Survey for students</h1>  
                    <br>
                    </div>
                    @isset($texts)
                    <div class="podnadpis col-md-12 thx">
                        <div class="offset-md-1 col-md-11">Thank you for your response.</div>
                    </div>
                    <br>
                    {{-- EVALUATION FOR THE WHOLE TEST --}}
                    <div class="offset-md-1 col-md-11">
                    @isset ($finaltext[0]) 
                            <strong>Total score: {{$totalresult}} / {{$max}}</strong>
                            <p class="">{!!$finaltext[0]->text!!}</p>
                            @for($i = 0; $i < $finaltext[0]->stars; $i++)
                                <i class="fas fa-star"></i>
                            @endfor
                        @endisset
                        <br>
                        
                    {{-- EVALUATION FOR SECTIONS     --}}         
                    {{-- @for ($i = 0; $i < count($sections); $i++)
                        @if($feedbacks[$i] || $texts[$i])
                        <hr>
                            <h4><strong>{{$sections[$i]->name}}</strong></h4> 

                            @if(count($texts[$i]) != 0)
                            <p>
                                <div class="sectfeedback">{!!$texts[$i][0]->text!!}</div><br>
                            @else
                            <div class="">No evaluation for this section has been made.</div><br>
                            </p>
                            @endif
                        @endif
                        
                    @endfor    --}}
                    <br>
                    <p><strong>Acknowledgement</strong></p>
                    <p>We are most grateful for your contribution to this tool. 
                    <br>
                    If you wish to provide feedback or contact the organizers of this tool, please send an email to inga.gaizauskaite@lstc.lt
                    </p>
                    <a href="/" class="link">ENAI page</a><br>
                    </div>    
                    @else
                    <script type="text/javascript">
                        window.location = "{{ url('/') }}";//here double curly brackets
                    </script>
                    <p>Session expired.</p>
                    @endisset
                    
                    
                    
                    
              </div>
           </div>
        </div>
</div>
@stop