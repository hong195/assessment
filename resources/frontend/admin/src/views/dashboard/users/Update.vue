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
      :model="user"
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
          rule: '',
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
      user: null,
    }),
    computed: {
      ...mapGetters('user', ['currentUser']),
      userId () {
        return this.$route.params.userId
      },
    },
    mounted () {
      this.finById(this.userId)
        .then(({ data }) => {
          this.user = data.data
        })
    },
    methods: {
      ...mapActions('user', ['updateUser', 'finById']),
      onSubmit () {
        this.updateUser({
          userId: this.userId,
          params: this.formValue,
        })
          .then(() => {
            this.$store.commit('successMessage', 'Пользователь создан')
            this.$router.push({
              name: 'users',
            })
          })
          .catch(() => {
            this.$store.commit('errorMessage', 'Ошибка создания пользователя')
          })
      },
    },
  }
</script>
