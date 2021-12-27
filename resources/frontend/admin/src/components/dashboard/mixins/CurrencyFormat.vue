<script>
  export default {
    name: 'CurrencyFormat',
    methods: {
      formatPrice (number) {
        return new Intl.NumberFormat().format(number)
      },
    },
  }
</script>

<style scoped>

</style>
