<template>
    <!-- <mdb-container> -->
        <mdb-pie-chart :data="pieChartData" :options="pieChartOptions" :width="600" :height="300"></mdb-pie-chart>
    <!-- </mdb-container> -->
</template>

<script>
    import { mdbPieChart, mdbContainer } from 'mdbvue';
    export default {
        name: 'ChartPage',
        components: {
        mdbPieChart,
        mdbContainer
        },
        data() {
            return {
                pieChartData: {
                labels: this.countries,
                datasets: [
                    {
                    data: this.countriesNum,
                    backgroundColor: ["#F7464A", "#46BFBD", "#FDB45C", "#949FB1", "#4D5360"],
                    hoverBackgroundColor: ["#FF5A5E", "#5AD3D1", "#FFC870", "#A8B3C5", "#616774"]
                    }
                ]
                },
                pieChartOptions: {
                responsive: false,
                maintainAspectRatio: false
                }
            };
        },
        props: {
            countries: Array,
            countriesNum: Array
        }
    }
</script>