<template>
  <div />
</template>
<script>
  import Swal from 'sweetalert2'

  export default {
    name: 'Swal',
    computed: {
      message: {
        get () {
          return this.$store.getters.message
        },
        set (val) {
          this.$store.commit('message', val)
        },
      },
    },
    watch: {
      message (value) {
        if (!value) {
          return
        }
        Swal.fire({
          text: value,
        })
          .then(() => {
            this.message = ''
          })
      },
    },
  }
</script>
