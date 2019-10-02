<template>   
    <div>
    <form @submit.prevent="addNewSection()">
        <table class="sections">
            <td>
                <div class="row parent">
                    <div class="">
                        <strong class="col-md-2">Add section:</strong>
                    </div>
                    <div class="col-md-3">
                        <input class="form-control" type="text" placeholder="Write section name" v-model="sectionname" required> 
                    </div>
                    <div class="col-md-3">
                        <input class="form-control test" type="number" placeholder="Write section weight" v-model="sectionweigth" required> 
                    </div>
                    <div class="col-md-3">
                        <button class="btn btn-outline-default btn-md" type="submit">Add section</button>
                    </div> 
                </div>
            </td>
        </table>
    </form>
    </div>
</template>

<script>
import EventBus from '../event-bus.js';

export default{
    data(){
        return{
            sectionname: '',
            sectionweigth: ''
        }
    },
    methods: {
        addNewSection(){
            if(this.sectionweigth != '' && this.sectionname != ''){
                axios.post('/api/addnewsection', {
                    name: this.sectionname,
                    weigth: this.sectionweigth
                })
                .then(response => {
                    EventBus.$emit('reload');
                    EventBus.$emit('showMsg', 'Section was added.', true);
                })
                .catch(e => console.log(e));
                this.sectionname = '';
                this.sectionweigth = '';
            }else{
                EventBus.$emit('showMsg', 'Empty values.', false);
            }
        }
    }
}
</script>

<style scoped>

.sections{
    width: 100%;
    margin-left: 0%;
    padding-right: 25%;
    border-radius: 5px;
    border: 1px solid black;
    border-collapse: collapse;
    box-shadow: 0 4px 8px 0 rgba(0, 0, 0, 0.2), 0 6px 20px 0 rgba(0, 0, 0, 0.19);
    margin-top: 1%;
    background: white;
}

td, th {
    padding: 15px;
    border: 1px solid black;
}

.vcenter {
    margin-top: 6px;
}

.parent {
    align-items: center;
}
</style>