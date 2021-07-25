<template>
  <v-container
    id="regular-forms"
    fluid
    tag="section"
    class="mt-3"
  >
    <default-form
      v-model="formValue"
      title="Добавить сотрудника"
      :schema="schema"
      :submit="onSubmit"
    />
  </v-container>
</template>

<script>
  import DefaultForm from '@/components/dashboard/DefaultForm'
  import { mapActions, mapGetters } from 'vuex'
  export default {
    name: 'CreateUpdate',
    components: {
      DefaultForm,
    },
    data: () => ({
      schema: [
        {
          attributes: [],
          component: 'text',
          label: 'Имя',
          name: 'first_name',
          placeholder: null,
          options: [],
          rule: 'required',
          value: null,
        },
        {
          attributes: [],
          component: 'text',
          label: 'Фамилия',
          name: 'last_name',
          placeholder: null,
          options: [],
          rule: 'required',
          value: null,
        },
        {
          attributes: [],
          component: 'text',
          label: 'Отчество',
          name: 'middle_name',
          placeholder: null,
          options: [],
          rule: 'required',
          value: null,
        },
        {
          attributes: {},
          component: 'text',
          label: 'Логин',
          name: 'login',
          placeholder: null,
          options: [],
          rule: 'required',
          value: null,
        },
        {
          attributes: {},
          component: 'text',
          type: 'password',
          label: 'Пароль',
          name: 'password',
          placeholder: null,
          options: [],
          rule: 'required',
          value: null,
        },
        {
          attributes: [],
          component: 'select',
          label: 'Роль',
          name: 'role',
          placeholder: null,
          options: [
            {
              id: 'admin',
              name: 'Админ',
            },
            {
              id: 'editor',
              name: 'Проверяющий',
            },
          ],
          rule: 'required',
          value: null,
        },
      ],
      formValue: null,
      pharmacies: [],
    }),
    computed: {
      ...mapGetters('user', ['currentUser']),
    },
    methods: {
      ...mapActions('user', ['createUser']),
      onSubmit () {
        this.createUser(this.formValue)
          .then(() => {
            this.$store.commit('successMessage', 'Пользователь создан')
            this.$router.push({
              name: 'pharmacies',
            })
          })
          .catch(() => {
            this.$store.commit('errorMessage', 'Ошибка создания пользователя')
          })
      },
    },
  }
</script>
