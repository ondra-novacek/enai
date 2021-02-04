<div v-show="showSection == -1" id="demo">
        <div class="container space shadowcopy">       
                <div class="row">  
                <div class="test okraj col-md-12"> 
                    <div class="row"> 
                            <div class="offset-md-1 col-md-11">  
                            <h1>Academic Integrity Self-Evaluation Tool for {{$whoName[0]->name}}s</h1>  
                            <br>
                            </div>

                            
                                <div class="podnadpis col-md-12">  
                                    <div class="offset-md-1 col-md-11 no-left-padding" style="padding: 0;">
                                    <h3>Demography</h3>
                                    </div>
                                </div>
            
    <div class="offset-md-1 col-md-10">
        
        <br>
        <label for="country"><strong>Country:</strong></label>
        <select class="custom-select" name='country' id='country'>
            <option value='not_specified'>not specified</option>
            <option v-for="country in countries" v-bind:value='country'>@{{country}}</option>
        </select>
        <br><br>     
        <label for="gender"><strong>Gender:</strong></label>
            <select class="custom-select" name='gender' id='gender'>
                <option value='not_specified'>not specified</option>
                <option value='male'>male</option>
                <option value='female'>female</option>
            </select>
        <br><br> 
        <label for="age"><strong>Age:</strong></label>
            <input type="number" name="age" class="form-control" id="age" min="15" step="1">
        <br>  

    @foreach ($demoquestions as $question)
    {{-- qtype = 5 rollup quesiton --}}
    @if ($question->qtype_id == 5)
        <label for="{{$question->id}}"><strong>{{$question->text}}</strong></label>
        <select class="custom-select" name="demo_{{$question->id}}" id="{{$question->text}}">
            @foreach ($question->option as $option)
                <option value="_{{$option->id}}">{{$option->name}}</option> 
            @endforeach
        </select>
    @endif

    {{-- qtype = 4 plain text quesiton --}}
    @if ($question->qtype_id == 4)
        <label for="{{$question->id}}"><strong>{{$question->text}}</strong></label>
        <input class="form-control" type="text" name="demo_{{$question->id}}">
    @endif
    
        <br><br>
    @endforeach

    <div class="form-check">
        <input class="form-check-input" type="checkbox" value="1" name="agreement" id="agreement">
        <label class="form-check-label" for="agreement">
               <small>I am giving my consent to save all my answers for further processing, analysis and research carried out by the European Network for Academic Integrity and/or its member institutions.</small>
        </label>
    </div>
    <br>
    <br>
    <div class="alert alert-info" role="alert" v-if="msg" v-text="msg">
    </div> 
    <div class="alert alert-info" role="alert" v-if="err" v-text="err">
    </div>
    
    <input type="hidden" name="_token" value="{{ csrf_token() }}"> 

    <button class="btn btn-secondary saveform" type="button" id="prevBtn" @click="nextPage(0)">Next</button>
</div></div></div></div></div>    
</div>{{-- end of div onepart --}}
{{-- END DEMO PART --}}