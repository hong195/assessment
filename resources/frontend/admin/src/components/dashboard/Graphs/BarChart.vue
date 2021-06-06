<script>
  import { Bar, mixins } from 'vue-chartjs'
  const { reactiveProp } = mixins
  export default {
    extends: Bar,
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
          scales: {
            yAxes: [{
              gridLines: {
                display: true,
                zeroLineColor: '#004394',
              },
              ticks: {
                fontSize: 14,
                beginAtZero: true,
              },
            }],
            xAxes: [{
              gridLines: {
                display: true,
                zeroLineColor: '#004394',
              },
              ticks: {
                fontSize: 16,
                beginAtZero: true,
              },
            }],
          },
          legend: {
            display: false,
          },
          tooltips: {
            enabled: true,
            mode: 'single',
            callbacks: {
              label: function (tooltipItems, data) {
                return `${tooltipItems.yLabel} ${tooltipItems.yLabel > 1 ? 'баллов' : 'балл'}`
              },
            },
          },
          layout: {
            padding: {
              left: 0,
              right: 0,
              top: 30,
              bottom: 20,
            },
          },
          responsive: true,
          maintainAspectRatio: false,
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
