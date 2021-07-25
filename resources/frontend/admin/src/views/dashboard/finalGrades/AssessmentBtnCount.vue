<template>
  <v-btn small :color="color" class="added-count">
    {{ addedCount }} / {{ maxCount }}
  </v-btn>
</template>

<script>
  export default {
    name: 'AssessmentBtnCount',
    props: {
      addedCount: {
        type: Number,
        default: 0,
      },
    },
    data () {
      return {
        maxCount: 10,
      }
    },
    computed: {
      color () {
        if (this.addedCount === 0) {
          return 'black'
        } else if (this.addedCount < 5) {
          return 'red'
        } else if (this.addedCount > 5 && this.addedCount < 7) {
          return 'yellow'
        } else {
          return 'success'
        }
      },
    },
  }
</script>
<style scoped lang="scss">
.added-count {
  color: #fff;
  font-weight: bold;
}
</style>
