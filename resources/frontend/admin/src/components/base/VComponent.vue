<template>
  <section class="mb-12 text-center">
    <h1
      class="font-weight-light mb-2"
      style="color:#3c4858; font-size:24px"
      v-text="`${heading}`"
    />
  </section>
</template>

<script>
  export default {
    name: 'VComponent',

    props: {
      heading: {
        type: String,
        default: '',
      },
      link: {
        type: String,
        default: '',
      },
    },
  }
</script>
