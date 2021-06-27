<template>
  <v-container id="data-tables" tag="section">
    <base-v-component heading="Создание оценки сотрудника" />
    <base-material-card
      color="indigo"
      icon="mdi-vuetify"
      inline
      class="px-5 py-3"
    >
      <form-base
        ref="create-assessment-option"
        v-model="formValue"
        scope="create-assessment-option"
        :schema="schema"
        :on-submit="submit"
      />
    </base-material-card>
  </v-container>
</template>

<script>
  import { mapActions } from 'vuex'
  import FormBase from '@/components/form/FormBase'

  export default {
    name: 'CreateAssessment',
    components: { FormBase },
    data () {
      return {
        formValue: '',
        schema: [],
        criteria: [],
      }
    },
    mounted () {
      this.fetchAll()
        .then(({ data }) => {
          data.data.forEach((criterion) => {
            this.schema.push({
              attributes: [],
              component: 'radio',
              label: criterion.name,
              name: criterion.name,
              placeholder: null,
              options: criterion.options.map((option) => {
                return {
                  id: `${option.name}`,
                  label: option.name,
                  name: option.name,
                }
              }),
              rule: 'required',
              value: null,
            })

            this.schema.push({
              attributes: [],
              component: 'textarea',
              label: 'Примечание',
              name: criterion.name,
              placeholder: null,
              options: [],
              value: null,
            })
          })
        })
    },
    methods: {
      ...mapActions('criterion', ['fetchAll']),
      ...mapActions('finalGrade', ['createAssessment']),
      submit () {
        console.log(this.formValue)
        // this.createAssessment()
      },
    },
  }
</script>
