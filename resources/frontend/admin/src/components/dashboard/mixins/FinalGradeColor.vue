<script>
  export default {
    name: 'FinalGradeColor',
    methods: {
      getColor (scored, total) {
        const diff = scored / total * 100
        if (diff > 70) { return 'success' }
        if (diff > 50) { return 'warning' }
        return 'red'
      },
    },
  }
</script>
