<template>
    <div class="sect">
        <div v-bind:class="{blur: showAddQ}">
        <!-- EDIT SECTION -->
        <button class="btncust btn btn-outline-dark btn-md my-2 my-sm-0 ml-3" @click="showSectionEdit()" title="Click to edit section name and weight">
            <div v-if="showedit === false">Edit section name and value <i class="fas fa-edit"></i></div>
            <div v-else>Hide section edit</div>
        </button>
        
        <form @submit.prevent="changeSection()" v-if="showedit" style="display:inline-block">
            <div class="row oneliner">
                <div class="col-md-4">
                    <input type="number" class="form-control custominput" placeholder="New section value" v-model="sectionvalue">
                </div>
                <div class="col-md-4">
                    <input type="text" class="form-control"placeholder="New section name" v-model="sectionname">
                </div>
                <div class="col-md-2">
                    <button type="submit" class="btn btn-default btn-md">Save</button>
                </div>       
            </div>
            <span v-if="msg">{{msg}}</span>
        </form>
        <button class="btncust btn btn-outline-dark btn-sm" id="delSection" @click="deleteSection()">Delete section</button>
        <br> 
        <br>
         <!-- QUESTIONS -->
         <!-- we want to display list of questions only if there are any connected to the section, else dont show -->
         <div v-if="section[0].text">
            <div v-for="(question,index) in section" class="grid-container" @mouseover="showArrows = question.question_id" @mouseleave="showArrows = 0">
                <!-- <question @refresh="refresh()" v-for="question in section" :key="question.question_id" :qtypes="qtypes" :question="question" :opts="getOptions(question.question_id)"></question> -->
                <div class="item1">
                        <!-- <button class="btncust btn btn-sm" style="text-align:left" @click="openModal(question.question_id)">Modal</button> -->
                    <button class="btncust btn btn-sm" style="text-align:left" title="edit this question" @click="questionClicked(index)"><i class="fas fa-edit"></i></button>
                    <button class="btncust btn btn-sm" style="text-align:left" title="delete this question" @click="deleteQuestion(question.question_id)"><i class="fas fa-trash"></i></button>
                    <button class="btncust btn btn-sm" style="text-align:left" title="change this question into different section" @click="shiftQuestionClicked(index)"><i class="fas fa-exchange-alt"></i></button>
                    
                    Q {{index+1}}: 
                </div>
               <div class="item2 qtext">
                    <span @click="questionClicked(index)">{{question.text}}</span>
               </div>
                <div class="item3">
                    <button class="btncust btn btn-sm" v-show="showArrows == question.question_id" @click="move(question, false)" style="float:right"><i class="fas fa-arrow-down"></i></button>
                    <button class="btncust btn btn-sm" v-show="showArrows == question.question_id" @click="move(question, true)" style="float:right"><i class="fas fa-arrow-up"></i></button>
                </div>
                    <!-- <sweet-modal overlay-theme="dark" modal-theme="red" :ref="'modal-' + question.question_id" width="80%">
                        <sweet-modal-tab title="Question" id="tab1">
                            <TestComponent></TestComponent>
                        </sweet-modal-tab>
                        <sweet-modal-tab title="Options" id="tab2">Contents of Tab 2</sweet-modal-tab>
                    </sweet-modal> -->
                
                
                <transition name="fade" >
                    <shiftquestion key="shift" v-if="showShiftQ === index" @close="showShiftQ = ''" :key="question.question_id" :question="question"></shiftquestion>
                    <question key="question" v-if="showQuestion === index" @close="showQuestion = ''" :key="question.question_id" :qtypes="qtypes" :question="question" :opts="getOptions(question.question_id)"></question>
                </transition>
            </div>
            <br>
        </div>
        <div v-else>
            <p>No questions have been created for this section in this survey.<br>Create or add one!</p>
        </div>
         <button class="btn btn-outline-default btn-md btncust" @click="toggleAddQ">Add question <i class="fas fa-plus"></i></button>
        </div>

         <transition name="fade" >
                <addquestion @close="showAddQ = false" :ids="section[0].subsection_id" :isNotDemo="true" :qtypes="qtypes" v-if="showAddQ"></addquestion>
         </transition>
         
         <br>

    </div>
</template>

<script>
    import Question from './Question.vue';
    import ExampleComponent from './ExampleComponent.vue';
    import AddQuestion from './AddQuestion.vue';
    import ShiftQuestion from './ShiftQuestion.vue';
    //import TestComponent from './TestComponent.vue';
    import EventBus from '../event-bus.js';
    // import { SweetModal, SweetModalTab } from 'sweet-modal-vue';

    export default {
        data(){
            return {
                showedit: false,
                sectionname: '',
                sectionvalue: '',
                msg: '',
                showAddQ: false,
                showShiftQ: '',
                showQuestion: '',
                showArrows: 0
            }
        },
        components: {
            'question': Question,
            'addquestion': AddQuestion,
            'examplecomponent': ExampleComponent,
            'shiftquestion': ShiftQuestion,
            //TestComponent,
            // SweetModal,
		    // SweetModalTab
        },
        props: {
            section: Array,   
            options: [Object, Array],
            qtypes: Array
        },
        computed: {
            OneFromMany(){
                return this.section.filter(question => question.qtype_id == 1);
            },
            ManyFromMany(){
                return this.section.filter(question => question.qtype_id == 2);
            },
            TableOptions(){
                return this.section.filter(question => question.qtype_id == 3);
            }
        },
        methods: {
            getOptions(id){
                return this.options[id];
            },
            refresh(){
                EventBus.$emit('reload');
            },
            showSectionEdit(){
                this.showAddQ = false;
                this.showShiftQ = false;
                this.showedit ? this.showedit = false : this.showedit = true;
            },
            changeSection(){
                this.msg = '';
                if (this.sectionname != '' || this.sectionvalue != ''){
                    axios.post('/api/editsection', {
                        name: this.sectionname,
                        value: this.sectionvalue,
                        section_id: this.section[0].subsection_id
                    })
                    .then(response => {
                        EventBus.$emit('showMsg', 'Section edited.', true);
                        this.sectionname = '';
                        this.sectionvalue = '';
                        this.refresh();
                    })
                    .catch(e => {console.log(e)});
                }else{
                    EventBus.$emit('showMsg', 'Empty values.', false);
                }
            },
            toggleAddQ(){
                this.showAddQ ? this.showAddQ = false : this.showAddQ = true
            },
            deleteQuestion(id){
                let result = confirm('Do you really want to delete this question? If this question is connected also to other surveys, it will remain there.');
                if(result){
                    //if the question is only in one survey, the q is deleted, else it deletes only connection to survey
                    axios.post('/api/deletequestion', {
                        idwho: this.$route.params['id'],
                        idq: id
                        })
                    .then(response => {
                        this.refresh();
                        EventBus.$emit('showMsg', 'Section deleted.', true);
                    })
                    .catch(e => console.log(e));
                }

            },
            deleteSection(){
                axios.post('/api/deletesection', {section_id: this.section[0].subsection_id})
                .then(response => {
                    alert(response.data); 
                    this.refresh();
                })
                .catch(e => {console.log(e)});
            },
            questionClicked(index){
                this.showAddQ = false;
                this.showShiftQ = false;
                if(index === this.showQuestion){
                    this.showQuestion = '';
                }else{
                    this.showQuestion = index;
                }
            },
            shiftQuestionClicked(index){
                this.showAddQ = false;
                this.showQuestion = false;
                if(index === this.showShiftQ){
                    this.showShiftQ = '';
                }else{
                    this.showShiftQ = index;
                }
            },
            openModal(id){
                var name = 'modal-' + id;
                this.$refs[name][0].open();
            },
            // change order of sections in survey
            move (question, up) { // up == true => up; up == false => down;
                //is lowest/highest
                // const isEndPoint = this.isDisabled(question, up);
                // if (isEndPoint) {
                //     return false;
                // }

                //orderQ hasnt been declared yet
                if (question.orderQ === null) {
                    this.declareOrder(question, up);
                    return;
                }

                //zjistit order value
                let questionsStack = this.section;
                let oldOrder = question.orderQ;
                let questions = [];

                //add default order
                for (let i = 0; i < questionsStack.length; i++) {
                    const qst = questionsStack[i];
                    if (qst.orderQ === null) {
                        qst.orderQ = i+1;
                    }
                }

                // get section with nearest lower order (cz: zjistit sekci s nejblizsim mensim poradim)
                if (up) {
                    questionsStack.forEach(q => {
                        if (q.orderQ < oldOrder) {
                            questions.push(q);
                        }
                    })
                }
                else {
                    questionsStack.forEach(q => {
                        if (q.orderQ > oldOrder) {
                            questions.push(q);
                        }
                    })
                }
                
                let swapQuestion;
                up ? swapQuestion = this.maxOrderValueId(questions) : swapQuestion = this.minOrderValueId(questions);

                //test if its extrem value (cz: zjistit jestli to neni extrem)
                if (swapQuestion == null) return false;

                //swap values (cz: prohodit values)
                axios.post('/api/swapQuestionOrder', {
                    first: question,
                    second: swapQuestion
                })
                .then(response => {
                    this.refresh();
                })
                .catch(err => console.log(err));
            },
            maxOrderValueId(questions){
                let length = questions.length;
                let max = -1; //does not have to be -Infitity since the order value is unsigned
                let question = null;
                while (length--) {
                    if (questions[length].orderQ > max) {
                        max = questions[length].orderQ;
                        question = questions[length];
                    }
                }
                return question;
            },
            minOrderValueId(questions){
                let length = questions.length;
                let min = Infinity;
                let question = null;
                while (length--) {
                    if (questions[length].orderQ < min) {
                        min = questions[length].orderQ;
                        question = questions[length];
                    }
                }
                return question;
            },
            isDisabled(question, up){ //up = bool
                let questions = [];
                let questionsStack = this.section;
                questionsStack.forEach(q => {
                    questions.push(q);
                });
                let q = up ? this.minOrderValueId(questions) : this.maxOrderValueId(questions);
                return q.orderQ == question.orderQ;
            },
            declareOrder(question, up){
                axios.post('/api/declareQuestionsOrder', {
                    id: question.subsection_id,
                    questionid: question.question_id
                })
                .then(response => {
                    //console.log(response.data);
                    question.orderQ = response.data;
                    this.move(question, up);
                })
                .catch(err => console.log(err));
            }
        },
        mounted(){
        }
    }
</script>

<style scoped>

.sect{
    /*min-height: 150px;*/
}

.sweet-custom{
        background-color: red !important;
    }

.btn_custom{
    margin-bottom: 10px;
    padding: 5px;
}

.btn_custom:focus {
    outline: none !important;
}

.fade-enter-active, .fade-leave-active {
  transition: opacity 0.35s ease-out;
}

.fade-enter, .fade-leave-to {
  opacity: 0;
}

.blur {
  filter: blur(1px);
  opacity: 0.4;
}

.qtext {
    cursor: pointer;
    margin-top: 10px;
}

#delSection{
    float: right;
}

.cssbutton {
color: #20bf6b !important;
text-transform: uppercase;
background: #ffffff;
padding: 15px;
border: 4px solid #20bf6b !important;
border-radius: 4px;
display: inline-block;
}

.cssbuttongrey {
color: #525553 !important;
text-transform: uppercase;
background: #5255533f;
padding: 5px;
border: 4px solid #525553 !important;
border-radius: 6px;
display: inline-block;
}

.cssbutton:hover {
color: #494949 !important;
border-radius: 50px;
border-color: #494949 !important;
transition: all 0.3s ease 0s;
cursor: pointer;
}

.cssbuttongrey:hover {
color: #494949 !important;
border-radius: 50px;
border-color: #494949 !important;
transition: all 0.3s ease 0s;
cursor: pointer;
}

.grid-container{
    display: grid;
    grid-template-columns: 13% 80% 7%;
    grid-gap: 1px;
}

.item2 {
  grid-column-start: 2;
  grid-column-end: 3;
  text-align: left;
}

.btncust{
    padding-left: 10px !important;
    padding-right: 10px !important;
    margin-right: 0px !important;
    margin-left: 0px !important;
}

.col{
    padding-bottom: 0 !important;
}

.grid-templ{
    display: grid;
    align-content: center;
}

.oneliner{
    align-items: center;
}

.custominput {
    margin-left: 10px;
    margin-right: 0px;
}
</style>

