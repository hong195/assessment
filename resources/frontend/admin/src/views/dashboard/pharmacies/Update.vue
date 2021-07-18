<template>
  <v-container
    id="regular-forms"
    fluid
    tag="section"
    class="mt-3"
  >
    <default-form
      v-model="formValue"
      title="Обновление аптеки"
      :schema="schema"
      :model="pharmacy"
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
      pharmacy: null,
    }),
    mounted () {
      this.findById(this.$route.params.id)
        .then(({ data }) => {
          this.pharmacy = data.data
        })
        .catch(() => {
          this.$store.commit('errorMessage', 'Аптека не найдена')
        })
    },
    methods: {
      ...mapActions('pharmacy', ['updatePharmacy', 'findById']),
      onSubmit () {
        console.log({
          pharmacyId: this.pharmacy.id,
          params: this.formValue,
        })
        this.updatePharmacy({
          pharmacyId: this.pharmacy.id,
          params: this.formValue,
        })
          .then(() => {
            this.$store.commit('successMessage', 'Апетка обновлена')
          })
          .catch(() => {
            this.$store.commit('errorMessage', 'Ошибка создания аптеки')
          })
      },
    },
  }
</script>
