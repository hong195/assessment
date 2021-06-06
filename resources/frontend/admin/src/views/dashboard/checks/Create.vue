<template>
  <v-container
    id="regular-forms"
    fluid
    tag="section"
    class="mt-3"
  >
    <default-form :base-url="baseUrl"
                  next-route-name=""
                  title-create="Создать чек"
                  title-update="Обновить чек"
    />
  </v-container>
</template>

<script>
  import DefaultForm from '@/components/dashboard/DefaultForm'

  export default {
    name: 'CreateUpdate',
    components: {
      DefaultForm,
    },
    data: () => ({
      schema: [],
      formValue: null,
      baseUrl: 'checks',
    }),
    computed: {
      isUpdate () {
        return !!this.$route.params.id
      },
    },
  }
</script>
