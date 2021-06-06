<template>
  <v-btn :color="getColor(conversion)"
         rounded
         dark
         class="rating__btn"
         depressed
  >
    {{ `${conversion}` }}%
  </v-btn>
</template>
<script>
  import RatingColor from '@/components/dashboard/mixins/RatingColor'

  export default {
    name: 'Conversion',
    mixins: [RatingColor],
    props: {
      conversion: {
        type: String,
      },
    },
  }
</script>
