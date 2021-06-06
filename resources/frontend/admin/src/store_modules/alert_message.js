export default {
  state: {
    message: '',
    color: '',
    snackBar: '',
  },
  getters: {
    message: (state) => state.message,
    color: (state) => state.color,
    snackBar: (state) => state.snackBar,
  },
  mutations: {
    errorMessage (state, errorMessage) {
      state.message = errorMessage
      state.color = 'error'
    },
    successMessage (state, successMessage) {
      state.message = successMessage
      state.color = 'success'
    },
    infoMessage (state, infoMessage) {
      state.message = infoMessage
      state.color = 'info'
    },
    message (state, message) {
      state.message = message
    },
    color (state, color) {
      state.color = color
    },
    snackBar (state, snackBar) {
      state.snackBar = snackBar
    },
  },
  actions: {

  },
}
