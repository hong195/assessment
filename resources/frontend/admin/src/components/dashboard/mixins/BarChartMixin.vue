<script>
  import BarChart from '@/components/dashboard/Graphs/BarChart'

  export default {
    components: { BarChart },
    data () {
      return {
        chart: {
          labels: [],
          datasets: [
            {
              backgroundColor: '#2f8cff',
              borderWidth: 1,
              data: [],
            },
          ],
        },
      }
    },
    methods: {
      dynamicColor () {
        const r = Math.floor(Math.random() * 255)
        const g = Math.floor(Math.random() * 255)
        const b = Math.floor(Math.random() * 255)
        return 'rgba(' + r + ',' + g + ',' + b + ', 0.5)'
      },
      poolColors (a) {
        var pool = []
        for (let i = 0; i < a; i++) {
          pool.push(this.dynamicColor())
        }
        return pool
      },
    },
  }
</script>
