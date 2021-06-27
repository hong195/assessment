<template>
  <div />
</template>

<script>
  import { mapActions } from 'vuex'

  export default {
    name: 'CreateAssessment',
    methods: {
      ...mapActions('criterion', ['fetchAll']),
    },
  }
</script>
