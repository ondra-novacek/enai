<template>
<div class="modalwindow">
    
    <strong>Label for question:</strong><br> 
    <input class="form-control" @blur="editQuestionText($event.target.value)" :value="question.text">

    Question type:
    <div v-if="question.qtype_id == 4" class="inline">{{qtypes[0].name}}</div> 
    <div v-if="question.qtype_id == 5" class="inline">{{qtypes[1].name}}</div>
    <br><br>

    <!-- options -->
    <strong v-if="question.qtype_id == 5">Options:</strong>
    <div v-if="options">
        <div v-for="option in options">
            <div class="input-group">
                <div class="input-group-prepend">
                    <span class="input-group-text">Option text</span>
                </div>
                <input type="text" @blur="editOption(option)" class="form-control" placeholder="Enter a text"  v-model="option.name">
                <div class="input-group-append">
                    <button class="btn btn-outline-danger btn-md btncustt" @click="deleteOption(option.id)" type="button" title="delete this option"><i class="fa fa-times"></i></button>
                </div>
            </div>
        </div>   
    </div>
    <div v-if="options.length == 0 && question.qtype_id == 5">
        <p>No options have been created.</p>
    </div>   
    <br>

    <!-- add new options -->
    <div v-if="question.qtype_id == 5">
            <strong>Add new option:</strong><br>
            <form class="form-row" @submit.prevent="addOption()">
                    <div class="col-9">
                      <input type="text" class="form-control" placeholder="write option text here" v-model="newoption">
                    </div>                 
                  
                    <button type="submit" class="btn btn-md btn-outline-default col-2 addbutton">Add</button>
            </form>
        </div>
        <div v-else>
            <p>Options can not be added to this type of question.</p>
        </div>

        <div v-if="this.msg" id="msg">{{msg}}</div>

    <!-- close button -->
    <br>
    <button class="btn btn-outline-dark btn-md cssbutton" @click="$emit('close')">Save and close</button>
</div>
</template>


<script>
    import EventBus from '../event-bus.js';

    export default {
        data(){
            return {
                msg: '',
                newoption: ''
            }
        },
        props: {
            question: Object,
            options: Array,
            qtypes: Array
        },
        methods: {
            editQuestionText(value){
                axios.post('/api/editquestiontext', {
                    text: value,
                    id: this.question.id
                })
                .then(response => {
                    this.$emit('refresh');
                })
                .catch(e => {
                    console.log(e);
                });
            },
            addOption(){
                if(this.newoption){
                    axios.post('/api/addoption', {
                        question_id: this.question.id,
                        name: this.newoption,
                        points: 0
                    })
                    .then(response => {
                        this.newoption = '';
                        this.$emit('refresh');
                    })
                    .catch(e => {
                        console.log(e);
                    });
                    this.msg = '';
                }else{
                    this.msg = "Wrong inputs. Check again.";
                }
            },
            deleteOption(id){
                // console.log(id);
                axios.get('/api/deleteoption/' + id)
                .then(response => 
                    //this emit will call api to get newest data, however its emitig through a lot components on its way
                    //mby find better solution later on
                    this.$emit('refresh'))
                .catch(error => console.log(error));
                
            },
            editOption() {
                axios.post('/api/editoption', {id: this.option.id, value: 0, name: this.option.name})
                .then(response => console.log('success'))
                .catch(error => console.log(error));
            },
        }
    }

</script>


<style>
.modalwindow {
    background: rgb(236, 236, 236);
    color: black;
    padding: 20px;
    top: 15%;
    left: 20%;
    width: 60%;
    /* min-height: 60%; */
    position: fixed;
    z-index: 10;
    border: black solid 1px;
    overflow: auto;
    max-height: 75%;
    min-height: 400px;
} 

.inline {
    display: inline;
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

.cssbutton:hover {
    color: #ce2b2b !important;
    border-color: #ce2b2b !important;
    transition: all 0.3s ease 0s;
}

.btncustt{
    margin-top: 0;
    margin-bottom: 0;
    margin-left: 0;
}

.addbutton{
    margin-top: 0;
}
</style>