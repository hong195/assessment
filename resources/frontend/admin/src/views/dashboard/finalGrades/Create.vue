<template>
  <div class="text-center">
    <v-dialog
      v-model="dialog"
      width="500"
    >
      <v-card>
        <v-card-title class="text-h5 grey lighten-2">
          Создать Итоговую Оценку
        </v-card-title>
        <v-card-text>
          <v-container>
            <form-base
              v-model="formValue"
              scope="create-final-grade"
              label="llll"
              :schema="schema"
              :on-submit="submit"
            />
          </v-container>
        </v-card-text>

        <v-divider />
      </v-card>
    </v-dialog>
  </div>
</template>

<script>
  import FormBase from '@/components/form/FormBase'
  export default {
    name: 'Create',
    components: {
      FormBase,
    },
    data () {
      return {
        formValue: null,
        dialog: false,
      }
    },
    computed: {
      schema () {
        return [
          {
            attributes: [],
            component: 'select',
            label: 'Сотрудник Аптеки',
            name: 'employee_id',
            placeholder: null,
            rule: 'required',
            value: null,
          },
          {
            attributes: [],
            component: 'date',
            label: 'Месяц',
            name: 'month',
            placeholder: null,
            rule: 'required',
            value: null,
          },
        ]
      },
    },
    methods: {
      openPopupForm () {
        this.dialog = true
      },
      submit () {
        console.log(this.formValue)
      },
    },
  }
</script>
