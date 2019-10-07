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
                    <div class="podnadpis col-md-12 thx">
                        @isset (session()->get('finaltext')[0])
                            <div class="offset-md-1 col-md-11">Thank you for your response.</div>
                        @else
                            <div class="offset-md-1 col-md-11">Session expired.</div>
                        @endisset
                    </div>
                    <br>
                    {{-- EVALUATION FOR THE WHOLE TEST --}}
                    <div class="offset-md-1 col-md-11">
                    @isset (session()->get('finaltext')[0]) 
                        <strong>Total score: {{session('ptsRecieved')}} / {{session('max')}}</strong>
                        <p>{!!session()->get('finaltext')[0]['text']!!}</p>
                        @for($i = 0; $i < session()->get('finaltext')[0]['stars']; $i++)
                            <i class="fas fa-star"></i>
                        @endfor
                        <br>
                        <br>
                        <p><strong>Acknowledgement</strong></p>
                        <p>We are most grateful for your contribution to this tool. 
                        <br>
                        If you wish to provide feedback or contact the organizers of this tool, please send an email to inga.gaizauskaite@lstc.lt
                        </p>
                    @else
                        {{-- <script type="text/javascript">
                            window.location = "{{ url('/') }}";
                        </script> --}}
                        <p>Please visit our page to learn more about Academic Integrity.</p>
                    @endisset 
                        <a href="/" class="link">ENAI page</a><br>
                    </div> 
              </div>
           </div>
        </div>
</div>
@stop