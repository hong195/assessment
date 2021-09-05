<template>
  <div class="text-center">
    <v-dialog
      v-model="dialog"
      width="500"
    >
      <v-card>
        <v-card-title class="text-h5 grey lighten-2">
          Добавление примечания
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
  import FormBase from '@/components/form/FormBase'
  import { mapActions, mapMutations } from 'vuex'
  export default {
    name: 'TheDescriptionAdding',
    components: { FormBase },
    props: {
      finalGradeId: {
        type: String,
        required: true,
      },
    },
    data () {
      return {
        dialog: false,
        formValue: null,
        schema: [
          {
            attributes: {},
            component: 'textarea',
            name: 'description',
            label: 'Примечание',
            placeholder: 'Введите примечание',
            rule: '',
            value: null,
          },
        ],
      }
    },
    methods: {
      ...mapActions('finalGrade', ['addDescription']),
      submit () {
        this.addDescription({
          finalGradeId: this.finalGradeId,
          description: this.formValue.description,
        })
          .then(({ data }) => {
            this.$store.commit('successMessage', data.message)
            this.dialog = false
            this.$router.push({
              name: 'final-grades',
            })
          })
          .catch(data => {
            this.$store.commit('errorMessage', data.message)
          })
      },
      openDialog () {
        this.dialog = true
      },
    },
  }
</script>
