<template>
  <base-material-card
    color="success"
    icon="mdi-account"
    :title="title"
    class="px-5 py-3 mb-10"
  >
    <form-base
      ref="default-form"
      v-model="formValue"
      :schema="defaultSchema"
      scope="form"
      :on-submit="submit"
    />
  </base-material-card>
</template>
<script>
  import FormBase from '@/components/form/FormBase'
  import _ from 'lodash'
  export default {
    name: 'DefaultForm',
    components: {
      FormBase,
    },
    props: {
      title: {
        type: String,
        default: '',
      },
      model: {
        type: Object,
        default: () => {},
      },
      schema: {
        type: Array,
        default: () => [],
      },
      submit: {
        type: Function,
        required: true,
      },
    },
    data: () => ({
      formValue: null,
      defaultSchema: [],
    }),
    watch: {
      model (val) {
        if (_.isEmpty(val)) {
          return true
        }

        this.defaultSchema = []

        this.schema.forEach((field) => {
          if (val[field.name]) {
            field.value = val[field.name]
            this.defaultSchema.push(field)
          }
        })
      },
      formValue (val) {
        this.$emit('input', val)
      },
    },
    mounted () {
      this.defaultSchema = this.schema
    },
  }
</script>
