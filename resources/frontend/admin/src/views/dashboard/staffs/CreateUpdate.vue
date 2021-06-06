<template>
  <v-container
    id="regular-forms"
    fluid
    tag="section"
    class="mt-3"
  >
    <default-form :base-url="baseUrl"
                  :next-route-name="redirectUrl"
                  title-create="Добавить сотрудника"
                  title-update="Редактировать информацию о персонале "
    />
  </v-container>
</template>

<script>
  import DefaultForm from '@/components/dashboard/DefaultForm'
  import { mapGetters } from 'vuex'
  export default {
    name: 'CreateUpdate',
    components: {
      DefaultForm,
    },
    data: () => ({
      schema: [],
      formValue: null,
      baseUrl: 'users',
    }),
    computed: {
      ...mapGetters('user', ['currentUser']),
      isUpdate () {
        return !!this.$route.params.id
      },
      redirectUrl () {
        if (this.currentUser.role.id === 2) {
          return 'home'
        }
        return 'staff'
      },
    },
  }
</script>
