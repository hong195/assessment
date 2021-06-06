<template>
  <v-container
    id="regular-forms"
    fluid
    tag="section"
    class="mt-3"
  >
    <default-form :base-url="baseUrl"
                  next-route-name="pharmacy"
                  title-create="Создать аптеку"
                  title-update="Редактировать аптеку"
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
      baseUrl: 'pharmacies',
    }),
    computed: {
      isUpdate () {
        return !!this.$route.params.id
      },
    },
  }
</script>
