<template>
    <div class="pozadi">
        <!-- {{results}} -->
            <!-- <button class="btn btn-outline-danger btn-md buttonsright" @click="deleteResults()">Delete all results</button> -->
            <div class="dropdown buttonsright">
                <button class="btn btn-outline-default btn-md dropdown-toggle" type="button" id="dropdownMenu2" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    Export
                </button>
                <div class="dropdown-menu" aria-labelledby="dropdownMenu2">
                        <a class="customA" href="https://academicintegrity.eu/survey/edit/export_excel/excel">Excel</a>
                        <br>
                        <a class="customA" href="https://academicintegrity.eu/survey/edit/export_excel/csv">CSV</a>
                </div>
            </div>
            
            <!-- <a href="/edit/export_excel/excel" class="btn btn-outline-success buttonsright">Export to excel</a> -->
            <br>
            <button class="btn btn-sm btn-outline-elegant custbtnn" @click="toggleFilter"><i class="fas fa-filter"></i></button>
            <div v-if="showFilter" class="filter">
                <small class="">Number of rows displayed:</small>
                <input type="number" class="form-control col-md-1" value="50">
                <button class="btn btn-md btn-outline-default custbtnn">Show</button>
            </div>
            <br><br>
            <div v-if="msg">{{msg}}</div>
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>respondent id</th>
                    <th>age</th>
                    <th>gender</th>
                    <th>profession</th>
                    <th>country</th>
                    <th>question</th>
                    <th>selected answer</th>
                    <th>1-5 question type</th>
                    <th>points</th>
                    <th>submitted</th>
                </tr>    
            </thead>
            <tbody>
                <tr v-for="result in results">
                    <td>{{result.person_id}}</td>
                    <td v-if="result.person">{{result.person.age}}</td><td v-else>NULL</td>
                    <td>{{result.person.gender}}</td>
                    <td>{{result.person.qfor.name}}</td>
                    <td>{{result.person.country}}</td>
                    <td v-if="result.question">{{result.question.text}}</td><td v-else>NULL</td>
                    <td v-if="result.option">{{result.option.name}}</td><td v-else>NULL</td>
                    <td>{{result.one_to_five_selected}}</td>
                    <td>{{result.points}}</td>
                    <td>{{result.created_at}}</td>
                </tr>    
            </tbody>
        </table>

        <div v-if="results.length == 0">
            <p>No results available in database.</p>
        </div>
    </div>
</template>

<script>

export default {
    data(){
        return {
            results: [],
            people: [],
            msg: '',
            subsections: [],
            showFilter: false
        }
    },
    beforeRouteEnter (to, from, next) {
            //this needs to be done so the data is on the site when it renders
            next(vm => {
                //results
                axios.get('/api/getresults')
                .then(response => {vm.results = response.data; console.log(response.data)})
                .catch(e => console.log(e));
                
                // axios.get('/api/getSubmittedDates')
                // .then(response => {
                //     vm.months = Object.keys(response.data);
                //     vm.numbers = Object.values(response.data);
                // })
                // .catch(e => {console.log(e); return []})

                next();
            })
    },   
    methods: {
        deleteResults(){
            if (this.results.length !== 0){
                axios.delete('/api/deleteallresults')
                .then(response => {
                    this.msg = "All results deleted.";
                    this.refresh();
                })
                .catch(e => {console.log(e)});
            } else {
                this.msg = "No results to be deleted.";
            }
        },
        refresh(){
            axios.get('/api/getresults')
            .then(response => {this.results = response.data})
            .catch(e => console.log(e));
        },
        toggleFilter(){
            this.showFilter ? this.showFilter = false : this.showFilter = true
        }
    }
}
</script>

<style>
    .pozadi{
        margin-left: 5%;
        margin-right: 5%;
        padding: 3%;
    }

    .buttonsright{
        float: right
    }

    .customA{
        text-decoration: none;
        padding: 5px;
        color: black;
    }

    .filter{
        border: rgba(0, 0, 0, 0.568) 1px solid;
        padding: 2%;
    }

    .custbtnn{
        margin-left: 0;
    }
</style>