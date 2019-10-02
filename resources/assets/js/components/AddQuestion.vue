<template>
    <div class="modalwindow">
        <!-- adding new question -->
        <strong>ADD COMPLETELY NEW QUESTION:</strong>
        <form @submit.prevent="addNewQuestion()">
            <textarea @click="emptyMsgs()" class="custominput customarea" v-model="qtext" cols="70" rows="3" placeholder="Write question text here" required></textarea>
            <br>

            <strong v-if="isNotDemo">Weight:</strong>
            <input v-if="isNotDemo" v-model="qweight" class="custominput" type="number" placeholder="weight of the question" required>
                
            <strong>Qtype:</strong>
            <select v-model="qtypeselected" class="custominput" required>
                <option v-for="(type,index) in qtypes" :value="type.id">{{type.name}}</option>
            </select>

            <span v-if="qtypeselected == 3">
                <strong>Columns:</strong>
                <input v-model="qrateTo" class="custominput" type="number" placeholder="Num of cols" required>
            </span>
            
            <br><br>
            <button class="btn btn-md btn-default" type="submit">Add to section</button>
        </form>
        <br><span v-if="msg" class="alert alert-info">{{msg}}</span>

        <hr v-if="isNotDemo" class="custom_hr">

        <!-- adding existing question -->
        <strong v-if="isNotDemo">ADD EXISTING QUESTION:</strong>
        <form v-if="questions.length > 0 && isNotDemo" @submit.prevent="addExistingQuestion()">
            <p>Note that question will be added to original section.</p>
            <select class="form-control" v-model="questionselected" required>
                <option class="form-control" v-for="(question,index) in questions" :key="question.id" :value="question.id">
                        <span v-if="question.text.length > 120">
                                {{question.text.substring(0,120)}}...
                        </span>
                        <span v-else>
                                {{question.text}}
                        </span>
                </option>
            </select>

            <br>
            <button class="btn btn-md btn-default" type="submit">Add</button>
        </form>
        <div v-else><div v-if="isNotDemo">No unused questions from other surveys are available. Create a new one.</div></div>

        <!-- success message when existing question is added -->
        <br><span v-if="msgtwo" class="alert alert-info">{{msgtwo}}</span>

        <!-- close button -->
        <button class="btn btn-outline-dark cssbuttonclose" @click="close">Close <i class="fas fa-times"></i></button>
    </div>
</template>

<script>
import EventBus from '../event-bus.js';

export default{
    data(){
        return {
            qtypeselected: '',
            qtext: '',
            qweight: '',
            sectionselected: '',
            msg: '',
            msgtwo: '',
            questionselected: '',
            questions: [],
            qrateTo: ''
        }
    },
    props: {
        qtypes: Array,
        ids: Number,
        isNotDemo: Boolean
    },
    methods: {
        close(){
            this.$emit('close');
        },
        addNewQuestion(){
            if (this.qtypeselected == 3){
                if (this.qrateTo == ''){
                    this.qrateTo = 5
                }
            } else {
                this.qrateTo = null
            }
            if((this.qweight != '' || !this.isNotDemo) && this.qtext != '' && this.qtypeselected != ''){
                axios.post('api/addnewquestion', {
                text: this.qtext,
                weight: (this.qweight ? this.qweight : 0),
                qtype_id: this.qtypeselected,
                section: this.ids,
                whofor: this.$route.params['id'],
                rateTo: this.qrateTo
                }).then(response => {
                    EventBus.$emit('reload');
                })
                .catch(e => console.log(e));
                this.emptyAll();
                this.msg = 'Question has been succesfully added.';
            }else{
                this.msg = 'Missing value.';
            }          
        },
        addExistingQuestion(){
            if(this.questionselected != ''){
                axios.post('/api/addexistingquestion', {
                    question_id: this.questionselected,
                    whofor: this.$route.params['id']
                }).then(response => {
                    EventBus.$emit('reload');
                })
                .catch(e => console.log(e));

                this.getOtherQuestions();
                this.msgtwo = "Question added to a survey.";
            }
            
        },
        emptyAll(){
            this.qtext = '';
            this.qweight = '';
            this.qtypeselected = '';
            this.sectionselected = '';
        },
        emptyMsgs(){
            this.msg='';
            this.msgtwo='';
        },
        getOtherQuestions(){
            axios.get('/api/otherquestionsfetch', {params: {
                idwho: this.$route.params['id'],
                idsection: this.ids
            }})
            .then(response => {
                this.questions = response.data;
            })
            .catch(e => console.log(e));
        }
    },
    mounted(){
        this.getOtherQuestions();
    }
}
</script>

<style scoped>
    .custominput {
        border: solid rgb(122, 122, 122) 1px;
        border-radius: 4px;
        margin-top: 4px;
        padding: 4px;
        display: inline-block;
        min-height: 35px;
    }

    .customarea {
        width: 100%;
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

    .addbtn{
        padding-left: 15px;
        padding-right: 15px;
        padding-top: 7px;
        padding-bottom: 7px;
        border: black solid 1px;
        border-radius: 4px;
    }

    .modalwindow {
        background: rgb(236, 236, 236);
        color: black;
        padding: 20px;
        top: 15%;
        left: 20%;
        width: 60%;
        min-height: 60%;
        max-height: 75%;
        position: fixed;
        z-index: 10;
        border: black solid 1px;
        overflow: auto;
        /* text-align: center; */
    } 

    
.cssbuttonclose {
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
    position: absolute;
    bottom: 10px;
}

.cssbuttonclose:hover {
    color: #ce2b2b !important;
    border-color: #ce2b2b !important;
    transition: all 0.3s ease 0s;
}
</style>