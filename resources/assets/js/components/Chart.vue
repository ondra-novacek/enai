<template>
        <mdb-line-chart :data="lineChartData" :options="lineChartOptions" :width="600" :height="300"></mdb-line-chart>
</template>

<script>
    import { mdbLineChart, mdbContainer } from 'mdbvue';
    export default {
        name: 'ChartPage',
        components: {
        mdbLineChart,
        mdbContainer
        },
        data() {
        return {
            lineChartData: {
            labels: this.months,
            datasets: [
                {
                label: "Number of respondents",
                backgroundColor: "rgba(255, 99, 132, 0.4)",
                borderColor: "rgba(255, 99, 132, 1)",
                borderWidth: 0.8,
                data: this.numbers
                }
            ]
            },
            lineChartOptions: {
            responsive: false,
            maintainAspectRatio: false,
            scales: {
                xAxes: [{
                gridLines: {
                    display: true,
                    color: "rgba(0, 0, 0, 0.1)"
                }
                }],
                yAxes: [{
                gridLines: {
                    display: true,
                    color: "rgba(0, 0, 0, 0.1)"
                }
                }]
            }
            }
        };
        },
        props: {
            months: Array,
            numbers: Array
        },
        methods: {
            
        },
        
    }
</script>