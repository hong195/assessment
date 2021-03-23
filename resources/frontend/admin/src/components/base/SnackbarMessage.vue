<template>
  <v-snackbar
    v-if="message"
    v-model="snackBar"
    class="alert-message"
    :color="$store.getters.color"
    :timeout="5000"
  >
    <base-material-alert
      :dismissible="true"
      :color="$store.getters.color"
      class="ma-0"
      dark
    >
      {{ message }}
    </base-material-alert>
  </v-snackbar>
</template>
<script>
  export default {
    name: 'SnackbarMessage',
    data () {
      return {
        timeoutCallback: null,
        snackBar: false,
      }
    },
    computed: {
      message: {
        get () {
          return this.$store.getters.message
        },
        set (val) {
          this.$store.commit('message', val)
        },
      },
      classes () {
        return {
          // eslint-disable-next-line no-undef
          ...VSnackbar.options.computed.classes.call(this),
          'v-snackbar--material': true,
        }
      },
    },
    watch: {
      message (value) {
        this.snackBar = true
      },
      snackBar (val) {
        if (!val) {
          this.message = ''
        }
      },
    },
    methods: {
      closeSnackbar () {
        this.snackBar = false
      },
    },
  }
</script>
<style lang="scss">
.alert-message {
  .v-alert__content {
    text-align: center;
  }
}
</style>
