<template>
  <step-progress :steps="steps" active-color="var(--v-primary-base)" :current-step="currentStep" />
</template>

<script>
  import StepProgress from 'vue-step-progress'
  import 'vue-step-progress/dist/main.css'

  export default {
    name: 'StepProgressBar',
    components: {
      StepProgress,
    },
    props: {
      currentStep: {
        type: Number,
        default: 0,
      },
    },
    data () {
      return {
        steps: [1, 2, 3, 4, 5, 6, 7, 8, 9, 10],
      }
    },
  }
</script>

<style lang="scss">
  .step-progress {
    &__wrapper {
      margin-bottom: 40px;
      margin-top: 40px;
      transform: scale(0.8);
    }
    &__step--valid {
      span {
        color: white !important;
        opacity: 1;
        transform: none;
      }
     }
  }
</style>
