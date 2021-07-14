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
        this.$refs.obs.validate()
          .then(success => {
            if (!success) {
              return
            }

            this.onSubmit()
          })
          .catch(error => {
            console.log(error)
          })
      },
    },
  }
</script>
