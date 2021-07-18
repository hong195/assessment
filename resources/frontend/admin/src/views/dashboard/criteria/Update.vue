<template>
  <div class="text-center">
    <v-dialog
      v-model="dialog"
      width="500"
    >
      <v-card>
        <v-card-title class="text-h5 grey lighten-2">
          Обновление критерия
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
    name: 'Update',
    components: {
      FormBase,
    },
    props: {
      criterion: {
        type: Object,
      },
    },
    data () {
      return {
        formValue: null,
        dialog: false,
        schema: [
          {
            attributes: {},
            component: 'text',
            label: 'Название критерия',
            name: 'name',
            placeholder: null,
            options: [],
            rule: 'required',
            value: null,
          },
        ],
      }
    },
    watch: {
      criterion (val) {
        this.schema[0].value = val.name
      },
    },
    methods: {
      ...mapActions('criterion', ['updateCriterion']),
      openPopupForm () {
        this.dialog = true
      },
      submit () {
        this.updateCriterion({
          criterionId: this.criterion.id,
          params: this.formValue,
        })
          .then(() => {
            this.$store.commit('successMessage', 'Критерий Обновлен')
            this.$emit('updated-criterion')
            this.dialog = false
          })
          .catch((data) => {
            console.log(data)
            this.$store.commit('errorMessage', 'Ошибка удаления')
          })
      },
    },
  }
</script>
