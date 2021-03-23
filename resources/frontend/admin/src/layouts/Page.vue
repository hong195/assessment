<template>
  <v-app>
    <pages-core-app-bar />
    <pages-core-view />
  </v-app>
</template>

<script>
  import PagesCoreAppBar from '@/components/pages/core/AppBar'
  export default {
    name: 'PagesIndex',

    components: {
      PagesCoreAppBar,
      PagesCoreView: () => import('@/components/pages/core/View'),
    },

    created () {
      this.$vuetify.theme.dark = this.$route.name !== 'login'
    },

    beforeDestroy () {
      this.$vuetify.theme.dark = false
    },
  }
</script>
