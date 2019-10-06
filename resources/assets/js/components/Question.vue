<template>
    <div class="modalwindow">
            <div class="row">
                    <div class="form-group col-md-7">
                        <label for="qtext"><strong>Question text:</strong></label>
                        <textarea @blur="editQuestionText($event.target.value)" class="form-control" id="qtext" rows="7">{{question.text}}</textarea>
                    </div>
                   
                    <div class="form-group col-md-2">
                        <label for="qvalue"><strong>Q value:</strong></label>
                        <input id="qvalue" @blur="editQuestionValue($event.target.value)" type="number" class="form-control" :value="question.weight"> 
                    </div>

                    <!-- question type and possible change -->
                    <!-- WARNING - it spits out vue-warn, thtas inccorect, ignore it -->
                    <div class="col-md-2">
                        <span><strong>Q type:</strong></span>  
                        <div class="dropdown">
                                <button class="btn btn-outline-dark btn-md dropdown-toggle" type="button" id="dropdownMenu2" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                  {{qtypes[question.qtype_id-1].name}}
                                </button>
                                <div class="dropdown-menu" aria-labelledby="dropdownMenu2">
                                  <button  v-for="type in qtypes" @click="editQuestionType(type.id)" class="dropdown-item" type="button">{{type.name}}</button>
                                </div>
                        </div>
                    </div>

                    <div class="form-group col-md-1"  v-if="question.qtype_id == 3">
                        <label for="rateto" title="number of columns"><strong>Columns:</strong></label>
                        <input id="rateto" class="form-control" type="number" @blur="editQuestionRateTo($event.target.value)" placeholder="" :value="question.rateTo">
                    </div>
                    
                    <!-- <div class="col-md-2" v-if="question.qtype_id == 3">
                        <span><strong>Rate To:</strong></span> 
                        <input class="form-control" type="number" @blur="editQuestionRateTo($event.target.value)" placeholder="5" :value="question.rateTo">
                    </div> -->
                    
        </div> <!-- END OF FIRST ROW-->
        <span  v-if="question.qtype_id == 3">{{rateMsg}}</span>
        <div v-if="question.qtype_id == 3"><strong>Columns:</strong></div>
        <div class="row" v-if="question.qtype_id == 3">
            <div class="col" v-for="index in Number(question.rateTo)" :key="index">
                <!-- <label :for="index">Column {{index}}</label> -->
                <input placeholder="" :id="index" type="text" :value="findColumn(index)" @blur="editColumnValue(index, $event.target.value)" class="form-control form-control-sm">
            </div>
        </div>

        <br>
        
        <button class="btn btn-sm btn-outline-dark" name="oneFeedback" @click="oneFeedbackShow ? oneFeedbackShow = false : oneFeedbackShow = true" v-text="oneFeedbackShow ? 'One feedback for Q on' : 'One feedback for Q off'"></button>
        <form @submit.prevent="addOneFeedback()">
            <div v-if="oneFeedbackShow">
                <!-- {{question}} -->
                <!-- <input type="checkbox" name="oneFeedbackON" v-model=""> Use this type of feedback here -->
                Use this: <input type="checkbox" v-model="question.hasFeedback"> <br>
                Feedback original: <input type="text" v-model="question.feedbackOriginal" ><br>
                Feedback alternative: <input type="text" v-model="question.feedbackAlt"><br>
                Points req for alternative: <input type="number" step="0.1" name="ptsAlternative" v-model="question.feedbackSplitValue"><br>
                <!-- <input type="checkbox" name="equal" v-model="eqSignOriginal"> Equal value also for alternative ON -->
                <button type="submit" class="btn btn-sm">Save</button> <br>
                <span v-if="msgFB">{{msgFB}}</span>
            </div>
        </form>

        <hr>

        <!-- rows for qtype 3 -->
        <div v-if="question.qtype_id == 3">
            <strong>Rows:</strong><br>
            <!-- <ul class="custom_ul"> -->
                <!-- <li> -->
                    <!-- <oneoption :option="option"></oneoption> -->
                    
                    <ul class="list-group list-group-flush">
                        <li class="list-group-item"  v-for="option in opts" :key="option.id">
                            <!-- edit option name form -->
                            <span v-if="showEditOption == option.id">
                                <form action="" @submit.prevent="editRowText(option.id)">
                                    Rename column: <input v-model="newrowname" type="text">
                                    <button class="btn btn-sm btn-outline-dark">Save</button>
                                </form>
                            </span>

                            <span v-else @dblclick="editableOption(option.id)">{{option.name}}</span>
                            <span style="float: right; margin-left: 20px"><i @click="deleteoptionrow(option.id)" class="fas fa-trash"></i></span>
                            <span style="float: right"><i class="fas fa-edit" @click="toggleSubOption(option.id)"></i></span>
                            <div v-if="showSubOption == option.id">
                                <br>
                                <!-- {{option.suboption}} -->
                                <!-- <p v-for="index in Number(question.rateTo)" :key="index"> -->
                                <p v-for="(suboption, index) in option.suboption" :key="index">
                                    Col {{index+1}}: 
                                    Feedback <input type="text" @blur="editSuboptionFeedback($event.target.value, suboption.id)" :value="suboption.feedback"> 
                                    Pts <input type="number" @blur="editSuboptionValue($event.target.value, suboption.id)" :value="suboption.value">
                                </p>
                                <button v-if="option.suboption.length < question.rateTo" @click="addSuboption(option.id, option.suboption.length)" class="btn btn-sm btn-outline-dark plusbutton"><i class="fas fa-plus"></i></button>
                                <!-- <p>Col 1: Feedback <input type="text"> Pts <input type="text"></p> -->
                                <!-- <p>Col 1: Feedback <input type="text"> Pts <input type="text"></p> -->
                            </div>
                        </li>
                    </ul>
                    <br>
                <!-- </li> -->
            <!-- </ul> -->

        <!-- ADD NEW ROW -->
            <strong>Add new row:</strong><br>
            <form class="form-row" @submit.prevent="addOption()">
                    <div class="col-8">  
                        <input type="text" class="form-control" @click="clear()" placeholder="write row text here" v-model="newoption">
                    </div>

                    <div class="col-2">
                      <input class="form-control" @click="clear()" placeholder="option value <0-1>, e.g. 0.3" v-model="newpoints">
                    </div>                  
                    <button type="submit" class="btn btn-outline-default btn-md btncust col-1">Add</button>
            </form>
        </div>

        <!-- options -->
        <div v-else>
        <div class="row" >
            <div class="col-md-12"><strong>Options:</strong>
            <ul class="custom_ul">
                <li v-for="option in opts" :key="option.id">
                    <oneoption :option="option"></oneoption>
                </li>
            </ul>
            <div v-if="opts.length == 0">No options have been created for this question.</div>
            </div>
        </div> <!-- END OF SECOND ROW-->

        <!-- ADD OPTION SECTION -->
        <div >
            <strong>Add new option:</strong><br>
            <form class="form-row" @submit.prevent="addOption()">
                    <div class="col-6" v-if="question.qtype_id == 2">
                        <input type="text" class="form-control" @click="clear()" placeholder="write option text here" v-model="newoption">
                    </div>    
                    <div v-else="question.qtype_id == 2" class="col-8">  
                        <input type="text" class="form-control" @click="clear()" placeholder="write option text here" v-model="newoption">
                    </div>

                    <div class="col-2">
                      
                      <input class="form-control" @click="clear()" placeholder="option value <0-1>, e.g. 0.3" v-model="newpoints">
                       
                    </div>                  
                    <div class="col-2" v-if="question.qtype_id == 2">
                        <input  class="form-control" @click="clear()" placeholder="value when not checked (nullable)" v-model="newpointsNC">   
                    </div>

                    <button type="submit" class="btn btn-outline-default btn-md btncust col-1">Add</button>
            </form>
        </div>
        </div> 

        <!-- question note -->
        <div>
            <input type="text" class="form-control col-5" placeholder="add note to question" style="display: inline-block" v-model="question.note">
            <button class="btn btn-sm btn-outline-dark" @click="updateNote()">Update note</button>
        </div>

        <!-- add question cap -->
        <div>
            <input type="text" class="form-control col-5" placeholder="add max cap points" style="display: inline-block" v-model="question.capPts">
            <button class="btn btn-sm btn-outline-dark" @click="updateCapPts()">Update cap points</button>
        </div>

        <!-- msg -->
        <div v-if="this.msg" id="msg">{{msg}}</div>
        <br>
        <button class="btn btn-outline-dark btn-md cssbutton" @click="$emit('close');refresh()">Close <i class="fas fa-times"></i></button>
    </div>
</template>

<script>
    import Oneoption from './Oneoption.vue';
    import EventBus from '../event-bus.js';

    export default {
        data(){
            return {
                question_id: '',
                newoption: '',
                newpoints: '',
                newpointsNC: '',
                msg: '',
                qtype: {},
                rateMsg: '',
                showSubOption: '',
                showEditOption: '',
                newrowname: '',
                oneFeedbackShow: false,
                msgFB: ''
                // feedbackOriginal: '',
                // feedbackAlternative: '',
                // feedbackSplitPts: '',
                // eqSignOriginal: ''
            }
        },
        props: {
            question: Object,
            opts: Array,
            qtypes: Array
        },
        components: {
            'oneoption': Oneoption
        },
        methods: {
            editRowText(id){
                this.showEditOption = '';
                if (this.newrowname != '') {
                    axios.post('/api/editrowtext', {
                        name: this.newrowname,
                        option_id: id
                    })
                    .then(response => {
                        this.newrowname = '';
                        this.refresh();
                    })
                    .catch(e => {
                        console.log(e);
                    });
                }
                
                
            },
            editableOption(id){
                if (id == this.showEditOption) {
                    this.showEditOption = ''
                    return 0;
                }
                this.showEditOption = id;
            },
            editQuestionText(value){
                axios.post('/api/editquestiontext', {
                    text: value,
                    id: this.question_id
                })
                .then(response => {})
                .catch(e => {
                    console.log(e);
                });
            },
            editQuestionValue(value){
                axios.post('/api/editquestionvalue', {
                    value: value,
                    id: this.question_id
                })
                .then(response => {})
                .catch(e => {
                    console.log(e);
                });
            },
            editQuestionType(qtype_id){
                this.rateMsg = '';
                if(qtype_id != this.question.qtype_id){
                    axios.post('/api/editquestiontype', {
                        id: this.question_id,
                        qtype_id: qtype_id
                    })
                    .then(response => {
                        this.refresh();
                    })
                    .catch(e => console.log(e));
                }
            },
            editQuestionRateTo(rateToValue){
                if (rateToValue < 2 || rateToValue > 5){
                    this.rateMsg = "Minimum number of columns is 2, max is 5."
                    return 0
                }
                if(rateToValue != this.question.rateTo){
                    axios.post('/api/editquestionrateto', {
                        id: this.question_id,
                        rateTo: rateToValue
                    })
                    .then(response => {
                        this.refresh();
                    })
                    .catch(e => console.log(e));
                }
            },
            editSuboptionFeedback(feedback, id){
                axios.post('/api/editsuboptionfeedback', {
                    id: id,
                    feedback: feedback
                })
                .then(response => {this.refresh()})
                .catch(err => console.log(err))
            },
            editSuboptionValue(value, id){
                axios.post('/api/editsuboptionvalue', {
                    id: id,
                    value: value
                })
                .then(response => {this.refresh()})
                .catch(err => console.log(err))
            },
            editColumnValue(index, value){
                axios.post('/api/editcolumnvalue', {
                    index: index,
                    value: value,
                    question_id: this.question.question_id
                })
                .then(response => { //console.log(response.data);this.refresh() 
                })
                .catch(err => console.log(err))
            },
            findColumn(index){
                var result = this.question.question_column.filter(column => {
                    return column.columnNum === index
                })

                if (result.length > 0) {
                    return result[0].name
                }

                return ''
            },
            toggleSubOption(id){
                if (id == this.showSubOption) {
                    this.showSubOption = ''
                    return 0;
                }

                this.showSubOption = id;
            },
            deleteoptionrow(id){
                axios.post('/api/deleteoptionrow', {
                    id: id
                }).then(response => {
                    this.refresh()
                })
                .catch(error => console.log(error))
            },
            addSuboption(optionid, num){
                axios.post('/api/addsuboption', {
                    option_id: optionid,
                    columnNum: num+1
                }).then(response => {
                    this.refresh()
                })
                .catch(error => console.log(error))
            },
            addOption(){
                if(this.newoption && this.newpoints <= 5 && this.newpoints >= -5){
                    axios.post('/api/addoption', {
                        question_id: this.question_id,
                        name: this.newoption,
                        points: this.newpoints,
                        pointsNC: this.newpointsNC
                    })
                    .then(response => {
                        this.newoption = '';
                        this.newpoints = '';
                        this.newpointsNC = '';
                        EventBus.$emit('reload');
                    })
                    .catch(e => {
                        console.log(e);
                    });
                    this.msg = '';
                }else{
                    this.msg = "Wrong inputs. Check again.";
                }
            },
            deleteQuestion(){
                let result = confirm('Do you really want to delete this question? If this question is connected also to other surveys, it will remain there.');
                if(result){
                    //if the question is only in one survey, the q is deleted, else it deletes only connection to survey
                    axios.post('/api/deletequestion', {
                        idfor: this.$route.params['id'],
                        idq: this.question_id
                        })
                    .then(response => {
                        this.refresh();
                    })
                    .catch(e => console.log(e));
                }

            },
            addOneFeedback(){
                // valid inputs check

                // api connect, submit cols to correct question
                axios.post('/api/addOneFeedback', {
                    idq: this.question_id,
                    fbOriginal: this.question.feedbackOriginal,
                    fbAlt: this.question.feedbackAlt,
                    splitPts: this.question.feedbackSplitValue,
                    eqSignOriginal: false,
                    useThis: this.question.hasFeedback
                })
                .then((resp) => {
                    console.log('success one feedback');
                    this.msgFB = "Success";
                }).catch((err) => {
                    console.log(err);
                }) 
                
            },
            clear(){
                this.msg = '';
                this.msgFB = '';
            },
            updateNote(){
                axios.post('/api/updatenote', {
                    idq: this.question_id,
                    note: this.question.note
                })
                .then((resp) => {
                    console.log('success');
                })
                .catch((er) => {
                    console.log(er);
                })
            },
            refresh(){
                EventBus.$emit('reload');
            },
            updateCapPts(){
                axios.post('/api/updatecappts', {
                    idq: this.question_id,
                    cappts: this.question.capPts
                })
                .then((resp) => {
                    console.log('success');
                })
                .catch((er) => {
                    console.log(er);
                })
            },
            getQtype(){
                this.qtypes.forEach(element => {
                    if (element.id == this.question.qtype_id) {
                        this.qtype = element;
                        return;
                    }
                });
            }
        },
        mounted() {
            this.question_id = this.question.question_id;
        }
    }
</script>

<style scoped>

        textarea{
            max-width: 100%;
        }

        #msg{
            margin-top: 10px;
            color:rgb(201, 102, 89);
        }
    
        .custom_ul{
            list-style: none;
            padding: 0;
            margin-top: 10px;
            margin-bottom: 10px;
        }

        .custom_span{
            padding: 2%;
            margin: 2%;
        }

        .modalwindow {
        background: rgb(236, 236, 236);
        color: black;
        padding: 20px;
        top: 15%;
        left: 12.5%;
        width: 75%;
        /* min-height: 60%; */
        position: fixed;
        z-index: 10;
        border: black solid 1px;
        overflow: auto;
        max-height: 75%;
        } 

        .closebtn {
            margin-left: 43%;
            padding: 15px;
            font-size: 17px;
            border: black solid 1px;
            border-radius: 50%;
            display: block;
            cursor: pointer;
        }

        .closebtn:hover {
            border: solid 1px;
            background: rgb(221, 162, 154)
        }

        .cssbutton {
            /* color: #ce2b2b !important; */
            /* text-transform: uppercase;
            background: #ffffff;
            padding: 20px;
            border: 4px solid !important;
            color: #494949 !important;
            border-radius: 50px;
            border-color: #494949 !important; */
            display: inline-block;
            margin-left: 43%;
            cursor: pointer;
        }

        .btncust{
            margin-top: 0;
        }

        .fas:hover{
            cursor: pointer;
        }

        .plusbutton{
            padding-left: 10px;
            padding-right: 10px;
            margin-left: 0px;
        }
    
</style>