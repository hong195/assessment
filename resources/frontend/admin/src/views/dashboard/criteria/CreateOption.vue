<template>
  <div class="text-center">
    <v-dialog
      v-model="dialog"
      width="500"
    >
      <v-card>
        <v-card-title class="text-h5 grey lighten-2">
          Создание опции
        </v-card-title>
        <v-card-text>
          <v-container>
            <form-base
              ref="create-criterion-option"
              v-model="formValue"
              scope="create-criterion-option"
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
  export default {
    name: 'Create',
    components: {
      FormBase,
    },
    props: {
      id: {
        type: String,
        required: true,
      },
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
            label: 'Название опции',
            name: 'name',
            placeholder: null,
            options: [],
            rule: 'required',
            type: 'text',
            value: null,
          },
          {
            attributes: [],
            component: 'text',
            label: 'Значение',
            name: 'value',
            placeholder: null,
            options: [],
            rule: 'required',
            type: 'number',
            value: null,
          },
        ]
      },
    },
    methods: {
      ...mapActions('criterion', ['createOption']),
      openPopupForm () {
        this.dialog = true
      },
      submit () {
        const swalOptions = {}

        this.createOption({ id: this.id, params: this.formValue })
          .then(() => {
            swalOptions.text = 'Критерий создан'
            swalOptions.icon = 'success'
            this.$refs['create-criterion-option'].reset()
          })
          .catch(() => {
            swalOptions.text = 'Ошибка создания опции криетрия'
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
