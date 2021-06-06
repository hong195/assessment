<template>
  <v-container>
    <base-material-card
      color="green"
      icon="mdi-stairs"
      inline
      class="px-5 py-3 my-10"
    >
      <v-row>
        <v-col cols="6">
          <h2 class="mx-3 display-2">
            Рейтинг аптек по итогам {{ year }} года
          </h2>
        </v-col>
        <v-col cols="3">
          <v-select
            v-model="year"
            :items="years"
            label="Год"
            outlined
            @change="fetchData"
          />
        </v-col>
        <v-col cols="3">
          <v-select v-model="pharmacyId"
                    :items="pharmacies"
                    item-text="name"
                    item-value="id"
                    label="Аптека"
                    clearable
                    outlined
                    @change="fetchData"
          />
        </v-col>
      </v-row>
      <v-progress-linear
        v-if="isLoading"
        indeterminate
        color="primary"
      />
      <bar-chart ref="barChart" :chart-data="chart" />
    </base-material-card>
  </v-container>
</template>

<script>
  import MonthPicker from '@/components/dashboard/MonthPicker'
  import moment from 'moment'
  import TableChart from '@/components/dashboard/Graphs/TableChart'
  import BarChartMixin from '@/components/dashboard/mixins/BarChartMixin'
  import { mapActions, mapGetters } from 'vuex'
  export default {
    name: 'PharmacyByYear',
    components: { TableChart, MonthPicker },
    mixins: [BarChartMixin],
    data () {
      return {
        pharmacyId: null,
        year: parseInt(moment().format('YYYY')),
        items: [],
        isLoading: true,
      }
    },
    computed: {
      ...mapGetters({ pharmacies: 'getPharmacies' }),
      years () {
        const years = new Date().getFullYear()
        const arr = []
        for (let i = 2019; i <= years; i++) {
          arr.push(i)
        }
        return arr
      },
    },
    methods: {
      ...mapActions(['fetchAllPharmacies']),
      fetchData () {
        this.isLoading = true
        this.axios.get(`pharmacy-rating/${this.pharmacyId}`, {
          params: {
            year: this.year,
          },
        })
          .then(({ data }) => {
            this.isLoading = false
            this.items = data.data
            const filteredData = data.data
            this.chart.labels = moment.months()
            this.chart.datasets[0].data = Object.values(filteredData).map((pharmacy) => pharmacy ? pharmacy.scored : 0)
            this.chart.datasets[0].backgroundColor = this.poolColors(12)
            this.$refs.barChart.updateChart()
          }).catch(e => {
            this.isLoading = false
            console.error(e)
          })
      },

    },
    async mounted () {
      moment.locale('ru')
      await this.fetchAllPharmacies()
      this.pharmacyId = this.pharmacies[0].id
      this.fetchData()
    },
  }
</script>
