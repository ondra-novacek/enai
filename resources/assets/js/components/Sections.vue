<template>
    <div class="text-dark">
            <!-- SECTIONS -->
            <h5>Select a section:</h5>
            <table v-if="sections.length > 0" class="sections" id="sectionlist">
                <thead>
                    <th>Name of the section</th>
                    <th>Weight of the section</th>
                    <th>Used on survey:</th>
                </thead>
                <tbody>
                        <!-- DEMO SECTION -->
                        <tr class="hovercolorchange" @click="toggleDemoQuestions()"><td colspan=3>Demographic section <span style="float:right">(default)</span></td></tr>
                        <demographicsection  :qtypes="qtypesDemo" id="demo"></demographicsection>

                    <template v-for="(section, index) in sections">
                        <tr @click="rowClicked(section[0].subsection_id)" class="hovercolorchange" v-bind:class="{selected: rowShow[section[0].subsection_id]}">
                            <td><span v-if="section[0].text">{{index+1}}. </span>{{section[0].name}}</td>
                            <td>{{section[0].value}}</td>
                            <td>
                                <span v-if="section[0].text">
                                    <strong>This one</strong> 
                                    <button class="btncust2 btn btn-sm btn-outline-dark" @click="move(section[0], false)" @click.stop="rowClicked" style="float:right" :disabled="isDisabled(section[0], false)"><i class="fas fa-arrow-down"></i></button>
                                    <button class="btncust2 btn btn-sm btn-outline-dark" @click="move(section[0], true)" @click.stop="rowClicked" style="float:right;margin-right:5px" :disabled="isDisabled(section[0], true)"><i class="fas fa-arrow-up"></i></button>
                                </span>
                                <span v-else>not used here</span>
                            </td>
                        </tr>
                        <tr class="not-active" :class="{selectedTwo: rowShow[section[0].subsection_id]}"><!--  v-if="rowShow[section[0].subsection_id]"  -->
                            <td colspan="3" :class="'row-' + section[0].subsection_id"><sect :section="section" :options="options" :qtypes="qtypes"></sect></td>
                        </tr>
                    </template>
                </tbody>
            </table>
            <table id="nosection" class="sections" v-else>
                <td v-if="sendMsg">{{sendMsg}}</td>
                <td v-else>Loading data... </td>
            </table>
    </div>
</template>

<script>
    import Sect from './Sect.vue';
    import AddQuestion from './AddQuestion';
    import AddNewSection from './AddNewSection';
    import EventBus from '../event-bus.js';
    import DemographicSection from './DemographicSection.vue';

    export default {
        data(){
            return {
                showAddQuestion: false,
                rowShow: [],
                sectionMsg: 'Loading data ...',
                showSection: false //demo section
            }
        },
        components: {
            'sect': Sect,
            'addquestion': AddQuestion,
            'addnewsection': AddNewSection,
            'demographicsection': DemographicSection
        },
        props: {
            sections: Array,
            options: [Object, Array],
            qtypes: Array,
            sectionsOnly: Array,
            sendMsg: String,
            qtypesDemo: Array  
        },
        methods: {
            toggleDemoQuestions(){
                // this.showSection ? this.showSection = false : this.showSection = true;
                document.querySelector('.tdt').classList.toggle('active');
            },
            showAddQ(){
                this.showAddQuestion ? this.showAddQuestion = false : this.showAddQuestion = true;
            },
            refresh(){
                EventBus.$emit('reload');
            },
            fillStateRowShow(){
                this.sections.forEach(section => {
                    this.rowShow[section.subsection_id] = false;
                });
            },
            rowClicked(index){
                // set(array, key, value), sets always opposite value (true -> false, false -> true)
                this.$set(this.rowShow, index, !this.rowShow[index]);
                document.querySelector('.row-' + index).classList.toggle('activeRow');
            },
            // change order of sections in survey
            move (section, up) { // up == true => up; up == false => down;
                //zjistit order value
                let oldOrder = section.order;
                let sections = [];

                // get section with nearest lower order (cz: zjistit sekci s nejblizsim mensim poradim)
                if (up) {
                    this.usedSections.forEach(s => {
                        if (s[0].order < oldOrder) {
                            sections.push(s[0]);
                        }
                    })
                }
                else {
                    this.usedSections.forEach(s => {
                        if (s[0].order > oldOrder) {
                            sections.push(s[0]);
                        }
                    })
                }
                
                let swapSection;
                up ? swapSection = this.maxOrderValueId(sections) : swapSection = this.minOrderValueId(sections);

                //test if its extrem value (cz: zjistit jestli to neni extrem)
                if (swapSection == null) return false;

                //swap values (cz: prohodit values)
                axios.post('/api/swapSectionOrder', {
                    first: section,
                    second: swapSection
                })
                .then(response => {
                    this.refresh();
                })
                .catch(err => console.log(err));
            },
            maxOrderValueId(sections){
                let length = sections.length;
                let max = -1; //does not have to be -Infitity since the order value is unsigned
                let section = null;
                while (length--) {
                    if (sections[length].order > max) {
                        max = sections[length].order;
                        section = sections[length];
                    }
                }
                return section;
            },
            minOrderValueId(sections){
                let length = sections.length;
                let min = Infinity;
                let section = null;
                while (length--) {
                    if (sections[length].order < min) {
                        min = sections[length].order;
                        section = sections[length];
                    }
                }
                return section;
            },
            isDisabled(section, up){ //up = bool
                let sections = [];
                this.usedSections.forEach(s => {
                        sections.push(s[0]);
                });
                let s = up ? this.minOrderValueId(sections) : this.maxOrderValueId(sections);
                return s.order == section.order;
            }
        },
        mounted() {
            //this.fillStateRowShow(); 
        },
        computed: {
            fullsections(){
                return this.sections.filter(section => !empty(section));
            },
            usedSections(){
                return this.sections.filter(section => section[0].text);
            }
        }
    }
</script>

<style scoped>

.centered{
    display: block;
    margin-left: auto;
    margin-right: auto
}

.my_header h2{
    display: inline;
}

.heading {
    margin: 2%;
    border-color:rgb(161, 58, 58);
    background-color: #FEFFFF;
    border-radius: 10px;
    padding: 2%;
    margin-bottom: 5%;
} 

.sections{
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

.hovercolorchange:hover{
    cursor: pointer;
    background-color: #c4bdbdb6;
}

#nosection {
    text-align: center;
}

td, th {
    padding: 15px;
    border: 1px solid black;
    border-top: 0px solid black;
    transition: 0.3s;
}

th{
    background-color: rgba(24, 24, 24, 0.815);
    color: white;
}

.selected {
    background-color: #c4bdbdb6 !important;   
    color: black !important; 
}

.disabled-btn {
    cursor: not-allowed;
    pointer-events: none;
    color: #cccccc;
    background-color: #ffffff;
}

.btncust2{
    padding-left: 10px !important;
    padding-right: 10px !important;
}

tr td div{
    max-height: 0;
    box-sizing: border-box;
    overflow: hidden;
    transition: max-height 0.3s, padding 0.3s;
}

tr td.activeRow div{
    max-height: 1000px;
    padding: 3px 10px;
    transition: max-height 0.6s, padding 0.6s;
    padding-top: 10px;
}

td.activeRow {
    border-bottom: 1px black solid;
    border-top: 1px black solid;
}

tr.not-active td {
    padding: 0px;
    border-bottom: 0px solid black;
    transition: border-bottom 0.3s;
}

tr.selectedTwo td {
    border-bottom: 1px solid black;
}

</style>