<template>
  <v-container
    id="regular-forms"
    fluid
    tag="section"
    class="mt-3"
  >
    <default-form
      v-model="formValue"
      title="Создать аптеку"
      :schema="schema"
      :submit="onSubmit"
    />
  </v-container>
</template>

<script>
  import DefaultForm from '@/components/dashboard/DefaultForm'
  import { mapActions } from 'vuex'

  export default {
    name: 'CreatePharmacy',
    components: {
      DefaultForm,
    },
    data: () => ({
      schema: [
        {
          attributes: [],
          component: 'text',
          label: 'Номер аптеки',
          name: 'number',
          placeholder: null,
          options: [],
          rule: 'required',
          value: null,
        },
        {
          attributes: [],
          component: 'text',
          label: 'Электронная почта',
          name: 'email',
          placeholder: null,
          options: [],
          rule: 'required',
          value: null,
        },
      ],
      formValue: null,
    }),
    methods: {
      ...mapActions('pharmacy', ['createPharmacy']),
      onSubmit () {
        this.createPharmacy({
          params: this.formValue,
        })
          .then(() => {
            this.$store.commit('successMessage', 'Апетка создана')
            this.$router.push({
              name: 'pharmacies',
            })
          })
          .catch(() => {
            this.$store.commit('errorMessage', 'Ошибка создания аптеки')
          })
      },
    },
  }
</script>
