<template>
  <div class="text-center">
    <v-dialog
      v-model="dialog"
      width="500"
    >
      <v-card>
        <v-card-title class="text-h5 grey lighten-2">
          Создание критерия
        </v-card-title>
        <v-card-text>
          <v-container>
            <form-base
              ref="create-criterion-form"
              v-model="formValue"
              scope="create-criterion-form"
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
            component: 'text',
            label: 'Слаг',
            name: 'name',
            placeholder: null,
            options: [],
            rule: 'required',
            value: null,
          },
          {
            attributes: [],
            component: 'text',
            type: 'number',
            label: 'Порядок',
            name: 'order',
            placeholder: null,
            options: [],
            rule: '',
            value: null,
          },
          {
            attributes: {},
            component: 'text',
            label: 'Название критерия',
            name: 'label',
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
            swalOptions.text = 'Критерий создан'
            swalOptions.icon = 'success'
          })
          .catch(() => {
            swalOptions.text = 'Ошибка создания критерия'
            swalOptions.icon = 'error'
          })
          .finally(() => {
            Swal.fire(swalOptions)
              .then(() => {
                this.$emit('added-criterion')
                this.$refs['create-criterion-form'].reset()
                this.dialog = false
              })
          })
      },
    },
  }
</script>
