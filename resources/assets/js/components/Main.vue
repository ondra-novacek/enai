<template>
    <div class="container bgrclr">
            <br><br>

        <!-- HEADING PART -->
        <heading :who="whoname"></heading>

        <!-- ALERTS -->
        <div class="alert alert-success" role="alert" v-if="successMsg">
                {{successMsg}}
        </div>
        <div class="alert alert-danger" role="alert" v-if="errorMsg">
                {{errorMsg}}
        </div>

        <!-- SECTIONS TABLE -->
        <sections :options="options" :sections="sections" :qtypesDemo="qtypesDemo" :sectionsOnly="sectionsOnly" :qtypes="qtypes" :sendMsg="sendMsg"></sections>

        <!-- ADD SECTION -->
        <addnewsection></addnewsection>

        <!-- DEMO SECTION -->
        <!-- <demographicsection :qtypes="qtypesDemo"></demographicsection> -->

        <!-- MORE OPTIONS -->
        <!-- <div class="fixed-btn" id="aside-btn" @click="showBtns ? showBtns = false : showBtns = true"><i v-if="showBtns" class="fa fa-minus"></i><i v-else class="fa fa-plus"></i></div>
        <a v-if="showBtns" class="fixed-btn" id="aside-one" title="deletes all sections and questions" @click="deleteAll()"><i class="fa fa-trash"></i></a>
        <a v-if="showBtns" class="fixed-btn" id="aside-two"><i class="fa fa-bolt"></i></a> -->

        <!-- <br> -->
        <!-- <button class="btn btn-light" @click="toTheTop()">To the top</button> -->
        <!-- <br> -->
    </div>
</template>

<script>
    import Heading from './Heading.vue';
    import Sections from './Sections.vue';
    import Sect from './Sect.vue';
    import AddNewSection from './AddNewSection';
    import EventBus from '../event-bus.js';
    // import DemographicSection from './DemographicSection.vue';

    export default {
        data (){
            return {
                surveyId:  this.$route.params['id'],
                options: {},
                sections: [],
                whoname: {},
                qtypes: [],
                qtypesDemo: [],
                sectionsOnly: [],
                showBtns: false,
                successMsg: '',
                errorMsg: '',
                sendMsg: ''
            } 
        },
        components: {
            'heading': Heading,
            'sections': Sections,
            'sect': Sect,
            'addnewsection': AddNewSection,
            // 'demographicsection': DemographicSection
        },
        beforeRouteEnter (to, from, next) {
            //this needs to be done so the data is on the site when it renders
            next(vm => {
                let id = vm.$route.params['id'];
                //options
                axios.get('/api/options/' + id)
                .then(response => vm.options = response.data)
                .catch(error => console.log(error))
                //sections along with questions
                axios.get('/api/sections/' + id)
                .then(response => {vm.sections = response.data})
                .catch(error => console.log(error))
                //sections
                axios.get('/api/subsectionsfetch')
                .then(response => vm.sectionsOnly = response.data)
                .catch(error => console.log(error))
                //whofor
                axios.get('/api/whoname/' + id)
                .then(response => vm.whoname = response.data[0])
                .catch(error => console.log(error))
                //qtypes
                axios.get('/api/qtypefetch')
                .then(response => {
                    vm.qtypes = response.data;
                    // filter qtypes, 123 for normal questions, 45 for demographic questions
                    vm.qtypesDemo = vm.qtypes.filter(qtype => qtype.id > 3);
                    vm.qtypes = vm.qtypes.filter(qtype => qtype.id < 4); 
                    vm.sections ? vm.sendMsg = "Loading data" : vm.sendMsg = "No sections have been created."; 
                })
                .catch(error => console.log(error))

                next();
            })
        },
        methods: {
            refresh() {
                let id = this.$route.params['id'];
                //options
                axios.get('/api/options/' + id)
                .then(response => this.options = response.data)
                .catch(error => console.log(error))
                //sections along with questions in it
                axios.get('/api/sections/' + id)
                .then(response => this.sections = response.data)
                .catch(error => console.log(error))
                //sections only
                axios.get('/api/subsectionsfetch')
                .then(response => this.sectionsOnly = response.data)
                .catch(error => console.log(error))
                //whofor
                axios.get('/api/whoname/' + id)
                .then(response => this.whoname = response.data[0])
                .catch(error => console.log(error))
            },
            toTheTop(){
                document.body.scrollTop = 0;
                document.documentElement.scrollTop = 0;
            },
            deleteAll(){
                //deletes all sections, questions, options
            },
            showSuccess(msg){
                this.successMsg = msg;
                let l = this;
                setTimeout(function(){l.successMsg = ''}, 2000);
            },
            showError(msg){
                this.errorMsg = msg;
                let l = this;
                setTimeout(function(){l.errorMsg = ''}, 2500); 
            }
        },
        mounted(){
                // reload data
                EventBus.$on('reload', () => {
                    this.refresh();
                });
                // show message
                EventBus.$on('showMsg', (msg, positive) => {
                    positive ? this.showSuccess(msg) : this.showError(msg);
                });
        }
    }
</script>

<style scoped>
.bgrclr{
    /* background-color: rgba(219, 87, 64, 0.795); */
    /* background-color: #29648A; */
    /* background-color: #557A95; */
    /* background-color: #3AAFA9; */
    padding-bottom: 5%;
}

.fixed-btn{
    left: 3%;
    position: fixed;
    display: inline-block;
    text-decoration: none;
    color: rgb(165, 165, 165);
    width: 50px;
    height: 50px;
    line-height: 50px;
    font-size: 20px;
    border-radius: 50%;
    text-align: center;
    vertical-align: middle;
    overflow: hidden;
    font-weight: bold;
    cursor: pointer;
    background-color: rgb(63, 63, 63);
}

#aside-btn{
    top: 20%;
}

#aside-one{
    top: 30%;
    background-color: rgb(139, 139, 139);
}

#aside-two{
    top: 40%;
    background-color: rgb(139, 139, 139);
}
</style>