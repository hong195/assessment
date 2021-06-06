<script>
  import { PolarArea, mixins } from 'vue-chartjs'
  const { reactiveProp } = mixins
  export default {
    extends: PolarArea,
    mixins: [reactiveProp],
    props: {
      chartData: {
        type: Object,
        default: null,
      },
    },
    data () {
      return {
        options: {
          legend: {
            display: false,
          },
          responsive: true,
        },
      }
    },
    mounted () {
      this.renderChart(this.chartData, this.options)
    },
    methods: {
      updateChart () {
        this.$data._chart.update()
      },
    },
  }
</script>
