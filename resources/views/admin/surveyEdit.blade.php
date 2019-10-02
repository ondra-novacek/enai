@extends('layouts.app')

@section('content')
<div class="container">
    <div class="col-md-8 offset-md-2 widerplease">
        {{-- heading, name of the survey --}}
        <h1><span id="surveyname" contenteditable="true">{{$whoName[0]->name}}</span></h1>
        <br>

        {{-- decsription of the survey --}}
        <input class="form-control" value="{{$whoName[0]->description}}">
        <small>Brief description of the survey.</small>
        </br></br>
        {{-- sections --}}
        @include('admin.surveySections', ['sections' => $sections, 'options' => $options])
        
        {{-- add a section --}}
        <button class="btn btn-success"><i class="fas fa-plus"></i></button> 
    </div>
    

</div>
@endsection

@include('javascript.mainjs')