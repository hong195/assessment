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
            <table-chart v-if="items.length" :date="date" :items="items" :is-loading="isLoading" />
          </v-tab-item>
        </v-tabs-items>
      </v-tabs>
    </base-material-card>
    <base-material-card
      v-if="criteriaToShow.length && showAttributeWheel"
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
    props: {
      showAttributeWheel: {
        type: Boolean,
        default: true,
      },
      pharmaciesIds: {
        type: Array,
        default: () => [],
      },
    },
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
        criteriaToShow: [],
      }
    },
    computed: {
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
      // fetchPharmacies()

      fetchData () {
        const date = moment(this.date)
        this.isLoading = true

        const params = {
          year: date.format('YYYY'),
          month: date.format('M'),
          test: 2323,
        }

        if (this.pharmaciesIds.length) {
          params.pharmaciesIds = this.pharmaciesIds
        }

        this.axios.get('pharmacies-final-grade', {
          params,
        })
          .then((data) => {
            this.isLoading = false
            this.items = data.data
            this.chart.labels = this.items.map((pharmacy) => pharmacy.number)
            this.chart.datasets[0].data = this.items.map(pharmacy => pharmacy.employees[0].final_grade.scored)

            this.chart.datasets[0].backgroundColor = this.poolColors(this.items.length)
            this.$refs.barChart.updateChart()
          }).catch(e => {
            this.isLoading = false
            console.error(e)
          })
      },
      async fetchCheckStatistics () {
        const date = moment(this.date)
        this.isLoading = true
        await this.axios.get('assessments-analytics', {
          params: {
            year: date.format('YYYY'),
            month: date.format('M'),
          },
        })
          .then(({ data }) => {
            const polarChardData = data.data

            this.criteriaToShow = []
            for (const key in polarChardData) {
              this.criteriaToShow.push({
                label: key,
                count: polarChardData[key],
              })
            }

            this.polarChart.labels = Object.keys(polarChardData)
            this.polarChart.datasets[0].data = Object.values(polarChardData)
            this.polarChart.datasets[0].backgroundColor = this.poolColors(polarChardData.length)
            this.$refs.polarChart.updateChart()

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
