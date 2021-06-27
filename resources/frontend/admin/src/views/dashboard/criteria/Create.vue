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
              ref="create-final-grade"
              v-model="formValue"
              scope="create-final-grade"
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
  import Swal from 'sweetalert2'
  import FormBase from '@/components/form/FormBase'
  import { mapActions } from 'vuex'
  import SelectField from '../../../components/form/fields/SelectField'
  export default {
    name: 'Create',
    components: {
      SelectField,
      FormBase,
    },
    data () {
      return {
        formValue: null,
        dialog: false,
        employees: [],
      }
    },
    computed: {
      schema () {
        return [
          {
            attributes: [],
            component: 'text',
            label: 'Название критерия',
            name: 'name',
            placeholder: null,
            options: [],
            rule: 'required',
            value: null,
          },
        ]
      },
    },
    methods: {
      ...mapActions('criterion', ['create']),
      openPopupForm () {
        this.dialog = true
      },
      submit () {
        const swalOptions = {}

        this.create(this.formValue)
          .then(() => {
            swalOptions.text = 'Итоговая оценка создана'
            swalOptions.icon = 'success'
          })
          .catch(() => {
            swalOptions.text = 'Ошибка создания итоговой оценки'
            swalOptions.icon = 'error'
          })
          .finally(() => {
            Swal.fire(swalOptions)
              .then(() => {
                this.$refs['create-final-grade'].reset()
                this.dialog = false
              })
          })
      },
    },
  }
</script>
