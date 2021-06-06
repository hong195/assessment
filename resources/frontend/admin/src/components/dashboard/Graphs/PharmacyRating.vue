<template>
  <v-container>
    <base-material-card
      color="primary"
      icon="mdi-poll-box"
      inline
      class="px-5 py-3 my-10"
    >
      <v-row>
        <v-col lg="6">
          <h2 class="mx-3 display-2">
            Рейтинг аптек за {{ formattedDate }}
          </h2>
        </v-col>
        <v-col lg="6">
          <month-picker v-model="date" />
        </v-col>
      </v-row>

      <v-tabs v-model="tab" centered>
        <v-tab href="#graph">
          График
        </v-tab>
        <v-tab href="#table">
          Таблица
        </v-tab>
        <v-tabs-items v-model="tab" style="margin-top: 15px;">
          <v-tab-item :value="'graph'">
            <v-progress-linear
              v-if="isLoading"
              indeterminate
              color="primary"
            />
            <bar-chart v-if="date" ref="barChart" :chart-data="chart" />
          </v-tab-item>
          <v-tab-item :value="'table'">
            <table-chart :date="date" :items="items" :is-loading="isLoading" />
          </v-tab-item>
        </v-tabs-items>
      </v-tabs>
    </base-material-card>
    <base-material-card
      v-if="criteriaToShow.length"
      color="green"
      icon="mdi-poll-box"
      inline
      class="px-5 py-3 my-10"
    >
      <v-row align="center">
        <v-col>
          <polar-chart ref="polarChart" :chart-data="polarChart" />
        </v-col>
        <v-col>
          <v-simple-table>
            <template v-slot:default>
              <tbody>
                <tr v-for="(item, index) in criteriaToShow" :key="index">
                  <td>{{ item.label }}</td>
                  <td>{{ item.count }}</td>
                </tr>
              </tbody>
            </template>
          </v-simple-table>
        </v-col>
      </v-row>
    </base-material-card>
  </v-container>
</template>

<script>
  import MonthPicker from '@/components/dashboard/MonthPicker'
  import moment from 'moment'
  import TableChart from '@/components/dashboard/Graphs/TableChart'
  import BarChartMixin from '@/components/dashboard/mixins/BarChartMixin'
  import PolarChart from '@/components/dashboard/Graphs/PolarChart'

  export default {
    name: 'PharmacyRating',
    components: { PolarChart, TableChart, MonthPicker },
    mixins: [BarChartMixin],
    data () {
      return {
        polarChart: {
          labels: [],
          datasets: [
            {
              backgroundColor: '#2f8cff',
              borderWidth: 1,
              data: [],
            },
          ],
        },
        criteria: {},
        date: {
          year: moment().format('YYYY'),
          month: moment().format('M'),
        },
        items: [],
        tab: 'graph',
        isLoading: true,
      }
    },
    computed: {
      criteriaToShow () {
        const arr = []
        this.polarChart.datasets[0].data.forEach((item, key) => {
          arr.push({ label: this.polarChart.labels[key][0], count: item })
        })
        return arr
      },
      formattedDate () {
        return moment(this.date.month).locale(this.$i18n.locale).format('MMMM')
      },
    },
    watch: {
      date () {
        this.fetchCheckStatistics()
        this.fetchData()
      },
    },
    methods: {
      fetchData () {
        const date = moment(this.date)
        this.isLoading = true
        this.axios.get('pharmacy-rating', {
          params: {
            year: date.format('YYYY'),
            month: date.format('M'),
          },
        })
          .then(({ data }) => {
            this.isLoading = false
            this.items = data.data
            const filteredData = data.data.filter(item => item.rating.scored)
            this.chart.labels = filteredData.map((pharmacy) => ([pharmacy.name]))
            this.chart.datasets[0].data = filteredData.map((pharmacy) => pharmacy.rating.scored)
            this.chart.datasets[0].backgroundColor = this.poolColors(filteredData.length)
            this.$refs.barChart.updateChart()
          }).catch(e => {
            this.isLoading = false
            console.error(e)
          })
      },
      sortCriteria (criteria) {
        Object.values(criteria).forEach(el => {
          if (el.use_in_rating) {
            let value = 0
            el.options.forEach(option => {
              if (value < parseInt(option.value)) {
                value = parseInt(option.value)
              }
            })

            el.options.forEach(option => {
              if (value === parseInt(option.value) && option.selected) {
                this.criteria[el.name].count++
              }
            })
          }
        })
      },
      async fetchCheckStatistics () {
        const date = moment(this.date)
        this.isLoading = true
        await this.axios.get('checks-analytics', {
          params: {
            year: date.format('YYYY'),
            month: date.format('M'),
          },
        })
          .then(({ data }) => {
            if (data.length) {
              const criteria = data[0].criteria

              Object.values(criteria).forEach(el => {
                if (el.use_in_rating) {
                  this.criteria[el.name] = { label: el.label, count: 0 }
                }
              })

              data.forEach(item => {
                this.sortCriteria(item.criteria)
              })

              this.polarChart.labels = Object.values(this.criteria).map((el) => ([el.label]))
              this.polarChart.datasets[0].data = Object.values(this.criteria).map((el) => el.count)
              this.polarChart.datasets[0].backgroundColor = this.poolColors(Object.values(this.criteria).length)
              this.$refs.polarChart.updateChart()
            }
            this.isLoading = false
          }).catch(e => {
            this.isLoading = false
            console.error(e)
          })
      },
    },
  }
</script>
<style >
.v-slide-group__content.v-tabs-bar__content {
  max-width: 250px;
  margin: 0 auto;
}
</style>
