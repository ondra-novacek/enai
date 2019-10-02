<template>
    <div class="modalwindow">
        <h2>Change section of a question:</h2>
        <label for="selectSec"><strong>Section:</strong></label>
        <select @mouseover="filterSections()" @click="msg=''"class="form-control" id="selectSec" v-model="sectionSelected">
            <option disabled>{{question.name}}</option>
            <option v-for="section in sections" :key="section.id" v-bind:value="section.id" v-cloak>{{section.name}}</option>
        </select>
        <br>
        <button @click="changeSection()" class="btn btn-default btn-md">Change</button>
        <span v-if="msg">{{msg}}</span>  
        <button class="btn btn-md btn-outline-dark closebtn" @click="$emit('close')">Close <i class="fas fa-times"></i></button>
    </div>
</template>

<script>
import EventBus from '../event-bus.js';

export default{
    data(){
        return {
            sectionSelected: '',
            sections: [],
            msg: ''
        }
    },
    props: {
        question: Object
    },
    methods: {
        getSubsections(){
            axios.get('/api/subsectionsfetch')
            .then(response => this.sections = response.data)
            .catch(e => {console.log(e)});
        },
        filterSections(){
            this.sections = this.sections.filter(section => section.id != this.question.subsection_id);
        },
        changeSection(){
            if(this.sectionSelected != ''){
                axios.post('/api/changequestionsection', {idq: this.question.question_id, ids: this.sectionSelected})
                .then(response => {
                    EventBus.$emit('reload');
                })
                .catch(e => {console.log(e)});
                this.msg = "Section changed.";
            }else{
                this.msg = "Please select a section first.";
            }
            
        }
    },
    mounted() {
        this.getSubsections();
        this.filterSections();
    }
}
</script>

<style scoped>
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

    .closebtn {
        margin-left: 43%;
        /* padding: 15px;
        font-size: 17px;
        border: black solid 1px;
        border-radius: 50%; */
        display: block;
        cursor: pointer;
        position: absolute;
        bottom: 10px;
    }

    .closebtn:hover {
        border: solid 1px;
        background: rgb(221, 162, 154)
    }

</style>