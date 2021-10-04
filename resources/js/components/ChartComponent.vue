<template>
    <div>
        <main id="app">
            <h1>Chart</h1>
            <div id="chart">
                <apexchart width="1000" type="line" :options="chartOptions" :series="series"></apexchart>
            </div>
        </main>
    </div>
</template>

<script>
import VueApexCharts from 'vue-apexcharts'

Vue.use(VueApexCharts)
Vue.component('apexchart', VueApexCharts)

export default {
    props: ['data', 'users'],
    data() {
        let categories = [];
        let completed = [];
        let user = this.users;

        for (let x = 0; x < user.length; x++) {
            categories = categories.concat(user[x].name);
            completed = completed.concat(user[x].completed);
        }
        return {
            postingList: this.data,
            userList: this.users,
            chartOptions: {
                xaxis: {
                    categories: categories,
                },
                yaxis: [{
                    labels: {
                        formatter: function(val) {
                            return val.toFixed(0);
                        }
                    }
                }]
            },
            series: [{
                name: 'Task completed',
                data: completed
            }],
        }
    },

}
</script>

<style scoped>
</style>