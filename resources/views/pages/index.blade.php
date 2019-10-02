@extends('main')

@section('content')

<div class="container">
      <div class="centered space">
            <a href="http://www.academicintegrity.eu"><img src="img/enai_full.png" class="enai"></a>
      </div>
      
      @if (session('msg'))
            <div class="alert alert-info">
                  {{ session('msg') }}
            </div>
      @endif
      <div class="row">
            <div class="col-md-3">
                  
                  <div class="card card-body bg-light">
                        <img src="img/student_avatar.png" class="who_picture">
                        <div style="min-height:240px">
                        <div class="centered"><h3>I am a student</h3></div>
                        @isset($descriptions[0]->description)
                              {{--<p>and I want to check my knowledge about good academic practice, plagiarism, and academic writing.</p>--}}
                               <p>and I want to check my knowledge about good academic practice, plagiarism, and academic writing.
                               
                               </p>
                               <br> 
                        @else
                             <p>and I want to check my knowledge about good academic practice, plagiarism, and academic writing. </p>
                        @endisset
                        <br>
                        </div>
                        <a class="btn btn-outline-info offset-md-4 col-md-4" href="{{route('student', ['student'])}}">Start</a>
                  </div>

            </div>
            <div class="col-md-3">
                  <div class="card card-body bg-light">
                        <img src="img/teacher_avatar.png" class="who_picture">
                        {{-- {{ Html::image('img/avatar-teacher.png', 'a picture') }} --}}
                        <div style="min-height:240px">
                        <div class="centered"><h3>I am a teacher</h3></div>
                        @isset($descriptions[1]->description)
                              <p>and I want to learn how good are my institutional policies and procedures for academic integrity and plagiarism prevention.</p>
                              <br>
                        @else
                             <p>and I want to check whether my teaching and assessment practice encourages honesty and prevent students' academic misconduct.</p>
                        @endisset
                        <br>
                        </div>
                        <a class="btn btn-outline-info offset-md-4 col-md-4" href="{{route('teacher', ['teacher'])}}">Start</a>
                  </div>
            </div>
            <div class="col-md-3">
                  <div class="card card-body bg-light">
                        <img src="img/management_avatar.png" class="who_picture">
                        <div style="min-height:240px">
                        <div class="centered"><h3>I am a manager</h3></div>     
                        @isset($descriptions[2]->description)
                                <p>and I want to learn how good are my institutional policies and procedures for academic integrity and plagiarism prevention.</p>
                        @else
                              <p>and I want to learn how good are my institutional policies and procedures for academic integrity and plagiarism prevention.</p>
                        @endisset
                        <br>
                        </div>
                        <a class="btn btn-outline-info offset-md-4 col-md-4" href="{{route('management', ['management'])}}">Start</a>
                  </div>
            </div>
            <div class="col-md-3">
                  <div class="card card-body bg-light">
                        <img src="img/researcher.png" class="who_picture">
                        <div style="min-height:240px">
                        <div class="centered"><h3>I am <br>a researcher</h3></div>
                        @isset($descriptions[1]->description)
                              <p>and I want to learn whether my research and publication practice follows required integrity standards.</p>
                        @else
                             <p>and I want to learn whether my research and publication practice follows required integrity standards.</p>
                        @endisset
                        <br>
                        </div>
                        <a class="btn btn-outline-info offset-md-4 col-md-4" href="{{route('researcher', ['researcher'])}}">Start</a>
                  </div>
            </div>
            </div>
      </div>
      <br>
      <div class="centered">
                  <button type="button" class="btn btn-info btn-lg" data-toggle="modal" data-target="#learnMore">Learn more</button>
      </div>
      
    <!-- Modal -->
    <div class="modal fade" id="learnMore" tabindex="-1" role="dialog" aria-labelledby="learnMoreTitle" aria-hidden="true">
      <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="exampleModalLongTitle">Learn more</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <div class="modal-body">
                  <p>Cras mattis consectetur purus sit amet fermentum. Cras justo odio, dapibus ac facilisis in, egestas eget quam. Morbi leo risus, 
                     porta ac consectetur ac, vestibulum at eros.</p>
                  <p>Praesent commodo cursus magna, vel scelerisque nisl consectetur et. Vivamus sagittis lacus vel augue laoreet rutrum faucibus dolor auctor.</p>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
          </div>
        </div>
      </div>
    </div>
@stop