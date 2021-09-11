<template>
  <v-container>
    <pharmacy-rating />
  </v-container>
</template>

<script>
  import PharmacyRating from '@/components/dashboard/Graphs/PharmacyRating'
  export default {
    name: 'SalesManagerPharmacies',
    components: { PharmacyRating },
  }
</script>
