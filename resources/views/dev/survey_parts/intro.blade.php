<div v-show="showSection == -2">
    <div class="container space shadowcopy">       
            <div class="row">  
            <div class="test okraj col-md-12"> 
                <div class="row"> 
                        <div class="offset-md-1 col-md-11">  
                            <h1>Academic Integrity: Survey for {{$whoName[0]->name}}</h1>  
                            <br>
                        </div>

                        
                        <div class="podnadpis col-md-12">  
                            <div  class="offset-md-1 col-md-11" style="padding: 0;">
                            <h3>Introduction</h3>
                            </div>
                        </div>
        
        <div class="offset-md-1 col-md-10">
        <br>    
        {!!$intro[0]->description!!}
        <br><br>
        <button class="btn btn-secondary" type="button" @click="nextPage(-1)">Continue</button>
    </div></div></div></div></div>    
</div>