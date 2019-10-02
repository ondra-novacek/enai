<template>
    <div class="container">
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
                        <select v-model="sectionselected" class="form-control col inputcust" required>
                            <option disabled selected value> -- select a section -- </option>
                            <option v-for="subsection in subsections" :key="subsection.id" :value="subsection.id">
                                {{subsection.name}}
                            </option>
                        </select>
                    </div>
                    
                    <button class="btn btn-outline-default btn-md custbtn">Add evaluation</button><br><br>
                    {{success}}
                </div>
            </div>       
        </form>    
        <br>

        <div v-for="section in evals" >
            <div v-if="section.length > 0" class="card" style="border: 1px solid black">
                <div class="card-body">
                    <span @click="rowClicked(section[0].subsection_id)" v-if="rowShow[section[0].subsection_id]" class="toggleAdd"><i class="far fa-minus-square"></i></span>
                    <span v-else @click="rowClicked(section[0].subsection_id)" class="toggleAdd"><i class="far fa-plus-square"></i></span>
                    <h5 class="card-title" >{{section[0].name}}</h5>  
                    <br> 
                    <div v-if="rowShow[section[0].subsection_id]">
                        <div v-for="eval in section">
                            <strong>Minimum points needed:</strong>{{eval.min_pts}}
                            <!-- <input type="checkbox" :value="eval.id" v-model="checkedEvals"><strong>Text:</strong> -->
                            <button type="button" class="btncust btn btn-sm btn-outline-stylish btncust" style="float:right" title="delete this question" @click="deleteEval(eval.id)"><i class="fas fa-trash"></i></button>
                            <br><br>
                            <div class="" v-if="!showEditTextArea[eval.id]" @dblclick="evalClicked(eval.id)">
                                <div v-html="eval.text"></div>
                            </div>
                            <div class="" v-else>
                                <!-- <textarea id="form18" class="md-textarea form-control" rows="3" v-html="eval.text"></textarea> -->
                                <vue-editor v-model="eval.text"></vue-editor>
                                <button type="button" class="btn btn-md btn-outline-default" @click="editEval(eval.id, eval.text)">Save</button>
                            </div>
                    <!-- <div v-html="eval.text"></div> -->
                            <hr>
                        </div>  
                    </div>       
                </div>   

                <!-- <div class="col-sm-4" v-if="rowShow[section[0].subsection_id]">
                    <button class="btn btn-danger customBtn" type="submit">Delete selected</button>
                </div> -->
                <!-- <br v-if="msg">
                <div v-if="msg" class="alert alert-info">{{msg}}</div> -->    
            </div>
            <br v-if="section.length > 0">
        </div>  
        </div>  
    </div>
</template>

<script>
    import { VueEditor } from 'vue2-editor';
    export default{
        data(){
            return{
                evals: [],
                checkedEvals: [],
                msg: '',
                subsections: [],
                sectionselected: '',
                pts: '',
                text: '',
                success: '',
                whoSelected: 1,
                fortypes: [],
                showForm: false,
                rowShow: [],
                showEditTextArea: []
            }
        },
        components: {
            VueEditor
        },
        methods: {
            getEvaluations(){
                axios.get('/api/getevaluations/' + this.whoSelected)
                .then(response => {
                    this.evals = response.data
                })
                .catch(e => console.log(e));
            },
            deleteEval(id){
                this.success = '';
                // if(this.checkedEvals.length != 0){
                    axios.post('/api/deleteevals', {ids: [id]})
                    .then(response => {this.getEvaluations();})
                    .catch(e => console.log(e));
                    
                    this.msg = "";
                // }else{
                //     // this.msg = "Nothing is selected.";
                //     alert('Nothing is selected. Tick the messages you wish to delete.')
                // }
            },
            editEval(ideval, texteval){
                axios.post('/api/editeval', {
                    id: ideval,
                    text: texteval
                })
                .then(response => {console.log(response.data)})
                .catch((e)=>{console.log(e)})

                this.evalClicked(ideval);
            },
            toggleForm(){
                this.showForm ? this.showForm = false : this.showForm = true
            },
            getSubsections(){
                axios.get('/api/subsectionsfetch')
                .then(response => {this.subsections = response.data})
                .catch(e => console.log(e));
            },
            getQFors(){
                axios.get('/api/surveys')
                .then(response => {
                    this.fortypes = response.data;
                });
            },
            addEval(){
                axios.post('/api/addevaluation', {
                    section_id: this.sectionselected,
                    pts: this.pts,
                    text: this.text,
                    idwho: this.whoSelected //qfor id, i.e. student
                })
                .then(response => {
                    this.getEvaluations();
                    this.success = 'Evaluation added successfully.';
                })
                .catch(e => {console.log(e)});

                this.text = '';
                this.pts = '';
            },
            showFor(id){
                this.whoSelected = id;
                this.msg = '';
                this.success = '';
                this.getEvaluations();
            },
            rowClicked(index){
                // set(array, key, value), sets always opposite value (true -> false, false -> true)
                this.$set(this.rowShow, index, !this.rowShow[index]);
            },
            evalClicked(index){
                this.$set(this.showEditTextArea, index, !this.showEditTextArea[index]);
            }
        },
        mounted(){
            this.getQFors();
            this.getEvaluations();
            this.getSubsections();
        },
    }
</script>

<style scoped>
    .evalHeader{
        display: inline;
    }

    .selected{
        color: white !important;
        /* background-color: rgba(25, 16, 58, 0.822) !important; */
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
    border: 1px rgb(68, 70, 69) solid
}

.darkblue{
    color: rgb(63, 68, 68);
}

.inputcust{
    margin: 0 15px 15px 15px;
}

.custbtn {
    margin: 0;
}

.toggleAdd{
    float: right; 
    font-size: 30px;
    color: #2BBBAD;
    cursor: pointer;
}

.btncust{
    padding-left: 10px !important;
    padding-right: 10px !important;
    margin-right: 0px !important;
    margin-left: 0px !important;
}
</style>