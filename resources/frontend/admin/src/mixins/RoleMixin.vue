<script>
  export default {
    name: 'RoleMixin',
    computed: {
      canManage () {
        return this.$store.state.user.isAdmin || this.$store.state.user.isEditor
      },
    },
  }
</script>
