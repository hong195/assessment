<script>
  export default {
    name: 'ValidatorMixin',
    data: () => ({
      text: '',
    }),
    methods: {
      validateForm (scope) {
        return this.$validator.validateAll(scope)
          .then((result) => {
            return new Promise((resolve, reject) => {
              return result ? resolve(result) : reject(result)
            })
          })
      },
    },
  }
</script>
