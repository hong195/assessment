<template>
  <v-container
    fluid
    tag="section"
    class="mt-3"
  >
    <default-form :base-url="baseUrl"
                  next-route-name="attribute-options"
                  title-create="Создать опцию"
                  title-update="Обновить опцию"
    />
  </v-container>
</template>
<script>
  import DefaultForm from '@/components/dashboard/DefaultForm'

  export default {
    name: 'Create',
    components: {
      DefaultForm,
    },
    data: () => ({
      schema: [],
      formValue: null,
      baseUrl: 'check-attribute-option',
    }),
  }
</script>

<style scoped>

</style>
