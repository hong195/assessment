export default {
  state: {
    hasPermission: false,
  },
  getters: {
    hasPermission: state => type => state.user.permissions.includes(type),
  },
}
