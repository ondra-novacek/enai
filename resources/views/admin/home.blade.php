{{-- @extends('main')

@section('content')

@endsection --}}
@extends('layouts.app')

@section('content')
<div class="container">
    
        <div class="col-md-8 offset-md-2">
            <h2>Edit surveys:</h2>
            
            <div class="col-md-4 ">
                @foreach ($surveys as $survey)
                    <a href="/admin/{{$survey->id}}" class="btn btn-secondary btn-block">{{$survey->name}}</a>
                @endforeach
            </div>


            </br>
            <div class="col-md-4 ">
            <button class="btn btn-success"><i class="fas fa-plus"></i></button>         
            </div>
            </br>
            <h2>Graphs and statistics:</h2>
                <div class="col-md-4 ">
                    <button class="btn btn-primary btn-block">Show</button>
                </div>
        </div>

    
</div>
@endsection

{{-- !!!MIGHT BE IN USE FOR LATER
<div class="card-header">Dashboard</div>

                <div class="card-body">
                    @if (session('status'))
                        <div class="alert alert-success">
                            {{ session('status') }}
                        </div>
                    @endif

                    You are logged in!
                </div> --}}