<script>
  export default {
    name: 'RatingColor',
    methods: {
      getColor (scored) {
        if (scored > 70) { return 'success' }
        if (scored > 50) { return 'warning' }
        return 'red'
      },
    },
  }
</script>

<style scoped>

</style>
