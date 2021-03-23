<script>
  export default {
    name: 'FormActionMixin',
    props: {
      onSubmit: {
        type: Function,
        default: ({ resolve }) => {
          resolve()
        },
      },
    },
    methods: {
      submit () {
        return new Promise((resolve, reject) => {
          this.onSubmit({ resolve: resolve, reject: reject })
        })
      },
      handle () {
        this.loading = true
        this.submit()
          .then(() => {
            // todo refactor submiting logic
            setTimeout(() => {
              this.loading = false
            }, 500)
          })
          .catch(error => {
            this.loading = false
            this.$emit('submit-failed', error)
            console.error(error)
          })
      },
    },
  }
</script>
