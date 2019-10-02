<template>
    <div>
        <div v-if="!showEditOption">
            <div class="input-group">
                <div class="input-group-prepend">
                        <!-- {{option}} -->
                    <span v-if="option.qtype_id != 2" class="input-group-text">Option text and value</span>
                    <span v-else="option.qtype_id != 2" class="input-group-text">Option text, value on checked, value on not checked</span>
                </div>
                <input type="text" @blur="editOption(option)" class="form-control" placeholder="Enter a text"  v-model="option.name">
                <input type="text" @blur="editOption(option)" class="form-control col-md-1" placeholder="Enter a value (0-1)" v-model="option.value">
                <button class="btn btn-outline-info btn-md btncust" @click="showOptionFeedback(1)" type="button" title="click to add or edit message being shown for choosing this option"><i class="far fa-comment"></i></button>
                <input v-if="option.qtype_id == 2" type="text" @blur="editOption(option)" class="form-control col-md-1" placeholder="Null" v-model="option.value_not_checked">
                <div class="input-group-append">
                <button v-if="option.qtype_id == 2" class="btn btn-outline-info btn-md btncust" @click="showOptionFeedback(0)" type="button" title="click to add or edit message being shown for NOT choosing this option"><i class="far fa-comment"></i></button>
                <button class="btn btn-outline-danger btn-md btncust" @click="deleteOption(option.id)" type="button" title="delete this option"><i class="fa fa-times"></i></button>
                </div>
            </div>
        </div>
        <!-- add or edit feedback message form (replacing other inputs) -->
        <div v-else>
            <div class="input-group">
                <div class="input-group-prepend">
                    <span class="input-group-text text-info">Feedback message</span>
                </div>
                <input v-if="isSelected == 1" type="text" class="form-control" placeholder="Enter a text (feedback for selecting)"  v-model="option.feedback">
                <input v-else type="text" class="form-control" placeholder="Enter a text (feedback for NOT selecting)"  v-model="option.feedback_not_checked">
                <button class="btn btn-outline-info btn-md btncust" @click="editOptionFeedback" type="button" title="">Confirm</button>
            </div>
        </div>
    </div>  
</template>

<script>
import EventBus from '../event-bus.js';

    export default {
        data() {
            return {
                showEditOption: false,
                isSelected: true
            }
        },
        props: {
            option: Object
        },
        methods: {
            deleteOption(id){
                console.log(id);
                axios.get('/api/deleteoption/' + id)
                .then(response => 
                    //this emit will call api to get newest data, however its emitig through a lot components on its way
                    //mby find better solution later on
                    EventBus.$emit('reload'))
                .catch(error => console.log(error));
                
            },
            editOption() {
                axios.post('/api/editoption', {id: this.option.id, value: this.option.value, name: this.option.name, valueNC: this.option.value_not_checked})
                .then(response => console.log('success'))
                .catch(error => console.log(error));
            },
            // isSelected = if the feedback is for not selected option or selected option by user
            showOptionFeedback(isSelected){
                this.isSelected = isSelected;
                this.showEditOption = this.showEditOption ? false : true;
            },
            editOptionFeedback(){
                if (this.option.feedback != "" || this.option.feedback_not_checked){
                    if (this.isSelected) {
                        axios.post('/api/editoptionfeedback', {id: this.option.id, feedback: this.option.feedback})
                        .then(response => console.log('success'))
                        .catch(error => console.log(error));
                    } else {
                        axios.post('/api/editoptionfeedbacknotsel', {id: this.option.id, feedback: this.option.feedback_not_checked})
                        .then(response => console.log('success'))
                        .catch(error => console.log(error));
                    }
                }
                this.showOptionFeedback();
            }
        },
        mounted(){
            // console.log(this.option);
        }
    }
</script>

<style scoped>
    .btncust{
    padding-left: 13px !important;
    padding-right: 13px !important;
    margin: 0;
}
</style>