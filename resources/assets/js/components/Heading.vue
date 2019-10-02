<template>
    <div class="heading">
        <div class="head">
            <h1 id="h1_custom">{{who.name}}</h1>
            <!-- <textarea @blur="editSurveyDesc($event.target.value)" class=""
                placeholder="Enter a brief description of this survey" style="margin-left:5%">{{who.description}}</textarea> -->
            <button class="btn btn-outline-dark" @click="toggleDesc()">Toggle description</button>
        </div>
         <br>
        <div v-if="showDesc">
            <vue-editor v-model="content"></vue-editor>
            <br>
            <button class="btn btn-default btn-md btncust" @click="editSurveyDesc()">Save</button>
            <br>
            <br>
        </div>    
    </div>
</template>

<script>
    import { VueEditor } from 'vue2-editor';
    export default {
        data(){
            return {
                content: '',
                showDesc: false
            }
        },
        components: {
            VueEditor
        },
        props: {
            who: Object    
        },
        methods: {
            editSurveyDesc(value){
                axios.post('/api/editsurveydesc', {
                    text: this.content,
                    id: this.who.id
                })
                .then(response => {this.showDesc = false})
                .catch(e => {
                    console.log(e);
                });
            },
            toggleDesc(){
                if (this.showDesc) {
                    this.showDesc = false
                } else {
                    this.getDesc()
                    this.showDesc = true
                } 
            },
            getDesc(){
                axios.get('/api/getDescription/' + this.who.id
                ).then((response)=>{
                    this.content = response.data.description
                }).catch((e)=>{
                    console.log(e)
                })
            }
        }
    }
</script>

<style scoped>
.heading {
    /* margin: 2%; */
    border:1px solid black;
    border-radius: 5px;
    background-color: #FEFFFF;
    box-shadow: 0 4px 8px 0 rgba(0, 0, 0, 0.2), 0 6px 20px 0 rgba(0, 0, 0, 0.19);
    /* border-radius: 10px; */
    padding: 2%;
    padding-bottom: 0;
    margin-bottom: 2%;
}  

.head{
    display: flex;
    align-items:center;
}

textarea {
    resize: none;
    border: none;
}

#h1_custom{
    /* flex: 2; */
    justify-content: center;
    margin-right: 5%;
    padding: 0;
    text-transform: uppercase;
}

.btncust{
    margin-right: 0px !important;
    margin-left: 0px !important;
}
</style>
