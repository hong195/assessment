export default {
  state: {
    message: '',
    icon: '',
    snackBar: '',
  },
  getters: {
    message: (state) => state.message,
    icon: (state) => state.icon,
    snackBar: (state) => state.snackBar,
  },
  mutations: {
    errorMessage (state, errorMessage) {
      state.message = errorMessage
      state.icon = 'error'
    },
    successMessage (state, successMessage) {
      state.message = successMessage
      state.icon = 'success'
    },
    infoMessage (state, infoMessage) {
      state.message = infoMessage
      state.icon = 'info'
    },
    message (state, message) {
      state.message = message
    },
    icon (state, icon) {
      state.icon = icon
    },
    snackBar (state, snackBar) {
      state.snackBar = snackBar
    },
  },
  actions: {

  },
}
