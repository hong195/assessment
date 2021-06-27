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
    },
  }
</script>
