<template>
  <router-view />
</template>

<script>
  export default {
    name: 'App',
  }
</script>
<style lang="scss">
@media (min-width: 480px) {
  .v-data-table__wrapper {
    td {
      max-width: 700px;
    }
  }
}
</style>
