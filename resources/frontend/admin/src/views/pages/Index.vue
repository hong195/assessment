<template>
  <v-app>
    <pages-core-view />
  </v-app>
</template>

<script>
  export default {
    name: 'PagesIndex',

    components: {
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
