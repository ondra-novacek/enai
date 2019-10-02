<template>
    <div class="container">
        <!-- <div v-for="fortype in fortypes" class="evalHeader">
            <button @click="showFor(fortype.id)" v-bind:class="{selected: fortype.id == whoSelected}" class="customBtn">{{fortype.name}}</button>
        </div> -->

        <div class="tab-slider--nav">
            <ul class="tab-slider--tabs">
                <li @click="showFor(fortype.id)" v-for="fortype in fortypes" class="tab-slider--trigger" v-bind:class="{tabselected: fortype.id == whoSelected}" rel="tab1">{{fortype.name}}</li>
            </ul>
        </div>
        <br><br>

        <form @submit.prevent="addEval()">
            <div class="formboard">
                <span @click="toggleForm()" v-if="showForm" class="toggleAdd"><i class="far fa-minus-square"></i></span>
                <span v-else @click="toggleForm()" class="toggleAdd"><i class="far fa-plus-square"></i></span>
                <legend class="darkblue">Add new feedback</legend>
                <div v-if="showForm">
                    <vue-editor v-model="text"></vue-editor>
                    <!-- <input type="text" class="form-control" placeholder="Evaluation text" v-model="text" required> -->
                    <br>
                    <div class="row">
                        <input type="number" class="form-control col inputcust" placeholder="Minimum points needed for this message to be displayed" v-model="pts" required>
                        <select v-model="stars" class="form-control col inputcust" required>
                            <option disabled selected value> -- select number of stars -- </option>
                            <option v-for="number in [1,2,3,4,5]" :value="number">
                                {{number}}
                            </option>
                        </select>
                    </div>
                    
                    <button class="btn btn-outline-default btn-md custbtn">Add evaluation</button><br><br>
                    {{success}}
                </div>
            </div>       
        </form>    
        <br><br>

            <div v-for="eval in evals" class="card spacebetween" style="border: 1px solid black">
                    <div class="card-body">
                        <button type="button" class="btncust btn btn-sm btn-outline-stylish btncust" style="float:right" title="delete this question" @click="deleteEval(eval.id)"><i class="fas fa-trash"></i></button>
                        <div class="" v-if="!showEditTextArea[eval.id]" @dblclick="evalClicked(eval.id)">
                            <div v-html="eval.text"></div>
                        </div>
                        <div class="" v-else>
                            <!-- <textarea id="form18" class="md-textarea form-control" rows="3" v-html="eval.text"></textarea> -->
                            <vue-editor v-model="eval.text"></vue-editor>
                            <button type="button" class="btn btn-md btn-outline-default" @click="editEval(eval.id, eval.text)">Save</button>
                        </div>   
                        
                        <strong>Points min:</strong> {{eval.min_pts}}
                        <br>
                        <strong>Stars:</strong> {{eval.stars}} 
                    </div>   
                <br>
            </div> 
    </div>
</template>

<script>
    import { VueEditor } from 'vue2-editor';
    export default{
        data(){
            return{
                evals: [],
                text: '',
                pts: '',
                success: '',
                stars: '',
                whoSelected: 1,
                fortypes: [],
                showForm: false,
                showEditTextArea: []
            }
        },
        components: {
            VueEditor
        },
        methods: {
            getEvaluationsFinal(){
                axios.get('/api/getevaluationsfinal/' + this.whoSelected)
                .then(response => {
                    this.evals = response.data
                })
                .catch(e => console.log(e));
            },
            deleteEval(id_eval){
                this.success = '';
                axios.post('/api/deleteeval', {id: id_eval})
                .then(response => {this.getEvaluationsFinal()})
                .catch(e => console.log(e));
            },
            getQFors(){
                axios.get('/api/surveys')
                .then(response => {
                    this.fortypes = response.data;
                });
            },
            toggleForm(){
                this.showForm ? this.showForm = false : this.showForm = true
            },
            addEval(){
                axios.post('/api/addevaluationfinal', {
                    stars: this.stars,
                    pts: this.pts,
                    text: this.text,
                    qfor_id: this.whoSelected
                })
                .then(response => {
                    this.getEvaluationsFinal();
                    this.text = '';
                    this.pts = '';
                    this.success = 'Evaluation added successfully.';
                })
                .catch(e => {console.log(e)});
                
            },
            showFor(id){
                this.whoSelected = id;
                this.msg = '';
                this.success = '';
                this.getEvaluationsFinal();
            },
            evalClicked(index){
                this.$set(this.showEditTextArea, index, !this.showEditTextArea[index]);
            },
            editEval(ideval, texteval){
                axios.post('/api/editevalfinal', {
                    id: ideval,
                    text: texteval
                })
                .then(response => {console.log(response.data)})
                .catch((e)=>{console.log(e)})

                this.evalClicked(ideval);
            }
        },
        mounted(){
            this.getEvaluationsFinal();
            this.getQFors();
        },
    }
</script>


<style>
        .evalHeader{
        display: inline;
    }

    .selected{
        color: white !important;
        background-color: rgba(25, 16, 58, 0.822) !important;
    }

    .customBtn{
        padding: 10px 6px;
        border: 1px solid rgba(25, 16, 58, 0.822);
        background-color: rgba(218, 221, 247, 0.829);
        color: rgba(25, 16, 58, 0.822);
        text-transform: uppercase;
        margin-bottom: 5px;
    }

    .tab-slider--nav{
	width: 100%;
	float: left;
	margin-bottom: 20px;
}
.tab-slider--tabs{
	display: block;
	float: left;
	margin: 0;
	padding: 0;
	list-style: none;
	position: relative;
	border-radius: 35px;
	overflow: hidden;
	background: #fff;
	height: 35px;
	user-select: none; 
}

.tab-slider--trigger {
	font-size: 12px;
	line-height: 1;
	font-weight: bold;
	color: #00695c;
	text-transform: uppercase;
	text-align: center;
	padding: 11px 20px;
	position: relative;
	z-index: 2;
	cursor: pointer;
	display: inline-block;
	user-select: none; 
}

.tabselected{
    content: "";
    background: #00695c;
    border-radius: 35px;
    color: white;
}

.formboard{
    padding: 20px; 
    border: 1px rgb(63, 68, 68) solid
}

.darkblue{
    color: rgb(63, 68, 68);
}

/* input, select{
    margin: 0 15px 15px 15px;
} */

.custbtn {
    margin: 0;
}

.toggleAdd{
    float: right; 
    font-size: 30px;
    color: #2BBBAD;
    cursor: pointer;
}

.spacebetween{
    margin-bottom: 5%;
}

.btncust{
    padding-left: 10px !important;
    padding-right: 10px !important;
    margin-right: 0px !important;
    margin-left: 0px !important;
}

.inputcust{
    margin: 0 15px 15px 15px;
}
</style>