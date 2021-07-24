<template>
  <div class="text-center">
    <v-dialog
      v-model="dialog"
      width="500"
    >
      <v-card class="update-dialog">
        <v-card-title class="text-h5 grey lighten-2">
          Обновление опции
        </v-card-title>
        <default-form
          v-model="formValue"
          :schema="schema"
          :model="option"
          :submit="submit"
        />
        <v-divider />
      </v-card>
    </v-dialog>
  </div>
</template>

<script>
  import DefaultForm from '@/components/dashboard/DefaultForm'
  import { mapActions } from 'vuex'
  export default {
    name: 'Update',
    components: { DefaultForm },
    props: {
      criteriaId: {
        type: String,
        required: true,
      },
      optionId: {
        type: String,
        default: null,
      },
    },
    data () {
      return {
        formValue: null,
        dialog: false,
        option: null,
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
    watch: {
      optionId (val) {
        if (!val) {
          return null
        }
        this.fetchOption({
          criterionId: this.criteriaId,
          optionId: val,
        })
          .then((data) => {
            this.option = data.data
          })
      },
    },
    methods: {
      ...mapActions('criterion', ['updateOption', 'fetchOption']),
      openPopupForm () {
        this.dialog = true
      },
      submit () {
        this.updateOption({
          criterionId: this.criteriaId,
          optionId: this.optionId,
          params: this.formValue,
        })
          .then(() => {
            this.$store.commit('successMessage', 'Опция обновлена')
          })
          .catch(() => {
            this.$store.commit('successMessage', 'Ошибка обновления опции')
          })
          .finally(() => {
            this.$emit('option-updated')
            this.dialog = false
          })
      },
    },
  }
</script>
<style lang="scss">
.update-dialog {
  .v-card--material__heading {
    display: none !important;
  }
  .v-card--material--has-heading {
    margin-bottom: 0 !important;
    border: none !important;
    box-shadow: none;
  }
}
</style>
