<template>
  <v-footer id="pages-core-footer" absolute color="transparent" dark>
    <v-container>
      <v-row align="center" no-gutters>
        <v-spacer class="hidden-sm-and-down" />

        <v-col cols="12" md="auto">
          <div class="body-1 font-weight-light pt-6 pt-md-0 text-center">
            &copy; 2020,
            <v-icon size="20">
              mdi-heart
            </v-icon>
            OXYmed
          </div>
        </v-col>
      </v-row>
    </v-container>
  </v-footer>
</template>

<script>
  export default {
    name: 'PagesCoreFooter',
  }
</script>

<style lang="sass">
#pages-core-footer
  a
    color: #FFFFFF
    font-size: .825rem
    font-weight: 500
    text-decoration: none
    text-transform: uppercase
</style>
