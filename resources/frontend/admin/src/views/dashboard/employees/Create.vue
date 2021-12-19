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
          component: 'select',
          label: 'Аптека',
          name: 'pharmacy_id',
          placeholder: null,
          options: [],
          rule: 'required',
          value: null,
        },
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
          attributes: {
            min: '1960-01-01',
          },
          component: 'date',
          label: 'Дата рождения',
          name: 'birthdate',
          placeholder: null,
          options: [],
          rule: 'required',
          value: null,
        },
        {
          attributes: [],
          component: 'select',
          label: 'Пол',
          name: 'gender',
          placeholder: null,
          options: [
            {
              id: 'male',
              name: 'Мужской',
            },
            {
              id: 'female',
              name: 'Женский',
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
      redirectUrl () {
        if (this.currentUser.role.id === 2) {
          return 'home'
        }
        return 'staff'
      },
    },
    mounted () {
      this.fetchAll()
        .then(({ data }) => {
          this.schema[0].options = data.data.map(pharmacy => {
            return {
              id: pharmacy.id,
              name: pharmacy.number,
            }
          })
        })
        .then(() => {
          this.schema[0].value = this.$route.params.pharmacyId
        })
    },
    methods: {
      ...mapActions('employee', ['createEmployee']),
      ...mapActions('pharmacy', ['fetchAll']),
      onSubmit () {
        this.createEmployee(this.formValue)
          .then(() => {
            this.$store.commit('successMessage', 'Сотрудник создан')
            this.$router.push({
              name: 'pharmacies',
            })
          })
          .catch(() => {
            this.$store.commit('errorMessage', 'Ошибка создания сотрудника')
          })
      },
    },
  }
</script>
