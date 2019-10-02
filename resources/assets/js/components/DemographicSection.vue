<template>
    <td colspan=3>
            <div class="demosection">    
                <div>
                    <button class="btn btn-sm btn-outline-secodary btncustone" style="text-align:left" title="edit this question" disabled><i class="fas fa-edit"></i></button>
                    <button class="btn btn-sm btn-outline-secodary btncustone" style="text-align:left" title="delete this question" disabled><i class="fas fa-trash"></i></button> 
                    <p style="display: inline">Label: Country <small><i>(this question is in form by default)</i></small></p>
                    <br>
                </div>
                <div class="questions">
                    <button class="btn btn-sm btn-outline-secodary btncustone" style="text-align:left" title="edit this question" disabled><i class="fas fa-edit"></i></button>
                    <button class="btn btn-sm btn-outline-secodary btncustone" style="text-align:left" title="delete this question" disabled><i class="fas fa-trash"></i></button> 
                    <p style="display: inline">Label: Gender <small><i>(this question is in form by default)</i></small></p>
                    <br>
                </div>
                <div class="questions">
                    <button class="btn btn-sm btn-outline-secodary btncustone" style="text-align:left" title="edit this question" disabled><i class="fas fa-edit"></i></button>
                    <button class="btn btn-sm btn-outline-secodary btncustone" style="text-align:left" title="delete this question" disabled><i class="fas fa-trash"></i></button> 
                    <p style="display: inline">Label: Age <small><i>(this question is in form by default)</i></small></p>
                </div>

                <div v-for="(question, index) in questions" class="questions">
                    <button class="btn btn-sm btn-outline-secodary btncustone" style="text-align:left" title="edit this question" @click="questionClicked(index)"><i class="fas fa-edit"></i></button>
                    <button class="btn btn-sm btn-outline-secodary btncustone" style="text-align:left" title="delete this question" @click="deleteQuestion(question.id)"><i class="fas fa-trash"></i></button>
                    <p class="clickable" @click="questionClicked(index)">Label: {{question.text}}</p>

                    <transition name="fade" >
                        <question key="question" v-if="showQuestion === index" @refresh="getDemographicQuestions()" @close="showQuestion = ''; getDemographicQuestions()" :key="question.id" :qtypes="qtypes" :question="question" :options="question.option"></question>
                    </transition>

                </div>
                <br>    
                <div>
                    
                    <button class="btn btn-outline-default btn-md btncustone" @click="toggleAddQ">Add question <i class="fas fa-plus"></i></button>
                </div>

                <transition name="fade" >
                    <addquestion @close="showAddQ = false; getDemographicQuestions()" :ids="1" :qtypes="qtypes" :isNotDemo="false" v-if="showAddQ"></addquestion>
                </transition>
                
            </div>    
        </td>
</template>

<script>
    import AddQuestion from './AddQuestion.vue';
    import EventBus from '../event-bus.js';
    import Question from './DemographicQuestion.vue';

    export default {
        data(){
            return {
                showSection: false,
                showAddQ: false,
                questions: [],
                showQuestion: '',
                index: ''
            }
        },
        components: {
            'addquestion': AddQuestion,
            'question': Question
        },
        props: {
            qtypes: Array
        },
        methods: {
            toggleDemoQuestions(){
                this.showSection ? this.showSection = false : this.showSection = true;
            },
            toggleAddQ(){
                this.showAddQ ? this.showAddQ = false : this.showAddQ = true
            },
            getDemographicQuestions(){
                axios.get('/api/getDemographicQuestions/' + this.$route.params['id'])
                .then(resp => {
                    // console.log(resp.data);
                    this.questions = resp.data;
                })
                .catch(err => {});
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
                        this.getDemographicQuestions();
                        EventBus.$emit('showMsg', 'Section deleted.', true);
                    })
                    .catch(e => console.log(e));
                }

            },
            questionClicked(index){
                this.showAddQ = false;
                if(index === this.showQuestion){
                    this.showQuestion = '';
                }else{
                    this.showQuestion = index;
                }
            }
        },
        mounted(){
            this.getDemographicQuestions();
        }
    }
</script>

<style scoped>
    /*
.demosection {
    padding: 15px;
}

.questions{
    margin-top: 5px;
}

.sections {
    width: 100%;
    margin-left: 0%;
    padding-right: 25%;
    border: 1px solid black;
    border-collapse: collapse;
    margin-top: 1%;
    background: white;
    border-radius: 5px;
    box-shadow: 0 4px 8px 0 rgba(0, 0, 0, 0.2), 0 6px 20px 0 rgba(0, 0, 0, 0.19);
}

td { 
    padding: 15px;
    border: 1px solid black;
}


th {
    background-color: #6B6E70;
    color: white;
}  

thead:hover {
    cursor: pointer;
}

tbody {
    padding: 5px;
    margin: 5px;
}
*/
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

.btn_custom {
    margin-top: 15px;
}

.clickable {
    cursor: pointer;
    display: inline;
}

.grid-container{
    display: grid;
    grid-template-columns: 13% 87%;
    grid-gap: 10px;
}

.item2 {
  grid-column-start: 2;
  grid-column-end: 3;
  text-align: left;
}

.btncustone{
    padding-left: 10px !important;
    padding-right: 10px !important;
    margin-right: 0px !important;
    margin-left: 0px !important;
}

</style>