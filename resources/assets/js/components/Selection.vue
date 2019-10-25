<template>
        <main>
    
        <input id="tab1" type="radio" name="tabs" checked>
        <label for="tab1"><i class="fas fa-clipboard-list"></i> Tests</label>
    
        <input id="tab2" type="radio" name="tabs">
        <label for="tab2">Feedback for sections</label>
    
        <input id="tab3" type="radio" name="tabs">
        <label for="tab3">Feedback overall</label>
    
        <input id="tab4" type="radio" name="tabs">
        <label for="tab4"><i class="fas fa-poll"></i> See results</label>

        <input id="tab5" type="radio" name="tabs">
        <label for="tab5"><i class="fas fa-info-circle"></i> Help</label>
    
        <section id="content1">
                <!-- <div class="card-deck" v-if="fortypes.length > 0">
                    <div class="card bg-light" v-for="fortype in fortypes">
                        <div class="card-body text-center uppercase">
                            <p class="card-text"><router-link :to="{name: 'edit', params: {id: fortype.id}}">{{fortype.name}}</router-link></p>
                        </div>
                    </div>    
                </div> -->
                <div class="card-deck" v-if="fortypes.length > 0">
                    <div class="card cardcust" v-for="fortype in fortypes">
                    <router-link :to="{name: 'edit', params: {id: fortype.id}}">
                        <div class="card-body btn-outline-default text-center uppercase">
                            <p class="card-text textcust">{{fortype.name}}</p>
                        </div>
                    </router-link>
                    </div>    
                </div>
                
                <div v-else>
                    <p>Loading ...</p>
                </div>
                <!-- <div class="col-md-4 "> -->
                <button v-if="notcreated" class="btn btn-success" @click="start()">Add student, teacher, management surveys</button>          
                <!-- <i class="fas fa-plus"></i> -->
                <!-- </div>     -->
                <br v-if="notcreated"><br>   
        </section>
    
        <section id="content2">
            <evaluations></evaluations>
        </section>
    
        <section id="content3">
            <evaluationsfinal></evaluationsfinal>
        </section>
    
        <section id="content4">
            <button class="btn btn-md btn-indigo" @click="routeToResults()">Show respondents data</button>
            <div class="dropdown buttonsright">
                    <button class="btn btn-primary btn-md dropdown-toggle" type="button" id="dropdownMenu2" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        Export data
                    </button>
                    <div class="dropdown-menu" aria-labelledby="dropdownMenu2">
                            <a class="customA" href="https://academicintegrity.eu/survey/edit/export_excel/excel">Excel</a>
                            <br>
                            <a class="customA" href="https://academicintegrity.eu/survey/edit/export_excel/csv">CSV</a>
                    </div>
                </div>
            <br>
            <hr>
            <h3>Graphs</h3>
            <button class="btn btn-md btn-outline-primary" @click="showChart ? showChart = 0 : showChart = 1">Number of respondents per month</button>
            <chart v-if="showChart" :months="months" :numbers="numbers"></chart>
            <br>
            <button class="btn btn-md btn-outline-secondary" @click="showChart2 ? showChart2 = 0 : showChart2 = 1">Respondents by country</button>
            <piechart v-if="showChart2" :countries="countries" :countriesNum="countriesNum"></piechart>
            
        </section>

        <section id="content5">
            <user-guide></user-guide>
        </section>
    
    </main>
    </template>
    
    <script>
        import UserGuide from './UserGuide.vue';
        import Evaluations from './Evaluations.vue';
        import EvaluationsFinal from './EvaluationsFinal.vue';
        import Chart from './Chart.vue';
        import PieChart from './PieChart.vue';

        export default {
            data (){
                return {
                    test: {},
                    surveyshow: false,
                    fortypes: [],
                    // surveyId: 1 //hardcoded
                    notcreated: false,
                    showChart: 0,
                    months: [],
                    numbers: [],
                    showChart2: 0,
                    countries: [],
                    countriesNum: []
                } 
            },
            components: {
                'user-guide': UserGuide,
                'evaluations': Evaluations,
                'evaluationsfinal': EvaluationsFinal,
                'chart': Chart,
                'piechart': PieChart
            },
            beforeRouteEnter (to, from, next) {
            //this needs to be done so the data is on the site when it renders
                next(vm => {
                    //results
                    // axios.get('/api/getresults')
                    // .then(response => {vm.results = response.data})
                    // .catch(e => console.log(e));
                    
                    axios.get('/api/getSubmittedDates')
                    .then(response => {
                        vm.months = Object.keys(response.data);
                        vm.numbers = Object.values(response.data);
                    })
                    .catch(e => {console.log(e);})

                    axios.get('/api/getRespondentsByCountry')
                    .then(response => {
                        vm.countries = Object.keys(response.data);
                        vm.countriesNum = Object.values(response.data);
                    })
                    .catch(e => {console.log(e);})

                    next();
                })
            }, 
            methods: {
                getData() {
                    axios.get('/api/surveys')
                    .then(response => {
                        this.fortypes = response.data;
                        if (this.fortypes.length == 0){this.notcreated = true}
                        // console.log(this.fortypes);
                    });
                },
                routeToResults(){
                    this.$router.push({name: 'unfilteredResults'});
                },
                routeToEvaluations(){
                    this.$router.push({name: 'evaluations'});
                },
                routeToFinalEvaluations(){
                    this.$router.push({name: 'finalevaluations'});
                },
                start(){
                    axios.get('/api/createsurveys')
                    .then(response => {
                        this.getData();
                    })
                    .catch(e => {});
                    this.notcreated = false;
                }
            },
            mounted(){
                this.getData();
                axios.get('/api/getRespondentsByCountry')
                    .then(response => {
                        //console.log(response.data);
                    })
                    .catch(e => {console.log(e); return []})
            }
        }
    </script>
    
    <style scoped>
    @import url('https://fonts.googleapis.com/css?family=Open+Sans:400,600,700');
    @import url('https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css');
    
    /* *,*::before,*::after{margin:0;padding:0;box-sizing:border-box;}html,body{height:100vh;}body{display:flex;align-items:center;justify-content:center;padding:40px;font:14px/1.5 'Open Sans',sans-serif;color:#345;background:#f0f2f4;} */
    
    p:not(:last-child) {
      margin: 0 0 20px;
    }
    
    main {
      max-width: 1200px;
      padding: 40px;
      border: 1px solid rgba(0,0,0,.2);
      background: #fff;
      box-shadow: 0 1px 3px rgba(0,0,0,.1);
      margin: auto;
      min-height: 900px;
    }
    
    section {
      display: none;
      padding: 20px 0 0;
      border-top: 1px solid #abc;
    }
    
    input {
      display: none;
    }
    
    label {
      display: inline-block;
      margin: 0 0 -1px;
      padding: 15px 25px;
      font-weight: 600;
      text-align: center;
      color: rgb(160, 165, 163);
      border: 1px solid transparent;
    }
    
    label:before {
      font-family: fontawesome;
      font-weight: normal;
      margin-right: 10px;
    }
    
    /* label[for*='1']:before { content: '\f1cb'; } */
    /* label[for*='2']:before { content: '\f17d'; } */
    /* label[for*='3']:before { content: '\f16c'; } */
    /* label[for*='4']:before { content: '\f171'; } */
    
    label:hover {
      color: #789;
      cursor: pointer;
    }
    
    input:checked + label {
      color: rgba(29, 29, 29, 0.822);
      border: 1px solid #abc;
      border-top: 2px solid rgba(29, 29, 29, 0.822);
      border-bottom: 1px solid #fff;
    }
    
    #tab1:checked ~ #content1,
    #tab2:checked ~ #content2,
    #tab3:checked ~ #content3,
    #tab4:checked ~ #content4,
    #tab5:checked ~ #content5 {
      display: block;
    }
    
    @media screen and (max-width: 800px) {
      label {
        font-size: 0;
      }
      label:before {
        margin: 0;
        font-size: 18px;
      }
    }
    
    @media screen and (max-width: 500px) {
      label {
        padding: 15px;
      }
    }

.tab-slider--nav{
	width: 100%;
	float: left;
	margin-bottom: 20px;
}
.tab-slider--tabs{
	display: block;
	float: left;
	margin: 0;
	padding: 0;
	list-style: none;
	position: relative;
	border-radius: 35px;
	overflow: hidden;
	background: #fff;
	height: 35px;
	user-select: none; 
}

.tab-slider--trigger {
	font-size: 12px;
	line-height: 1;
	font-weight: bold;
	color: #345F90;
	text-transform: uppercase;
	text-align: center;
	padding: 11px 20px;
	position: relative;
	z-index: 2;
	cursor: pointer;
	display: inline-block;
	user-select: none; 
}


.uppercase{
    text-transform: uppercase;
}

a{
    text-decoration: none;
    color: #2BBBAD;
}

.card, .carddeck, .cardbody{
    box-shadow: none !important;
}

    </style>
    
    