<template>
  <validation-provider
    v-slot="{ errors, required, ariaInput, ariaMsg }"
    tag="div"
    :rules="validationRule"
    :name="label"
    :vid="name"
  >
    <v-text-field
      v-model="innerValue"
      :name="name"
      :error-messages="errors"
      :type="type"
      v-bind="attributes"
      :label="label"
    />
  </validation-provider>
</template>

<script>

  import FieldMixin from '@/components/Form/Mixins/FieldMixin'
  import debounce from 'lodash.debounce'

  export default {
    name: 'TextField',
    mixins: [FieldMixin],
    props: {
      type: {
        type: String,
        default: 'text',
        validator: (type) => {
          return [
            'text',
            'email',
            'url',
            'tel',
            'search',
            'password',
            'hidden',
            'number'].indexOf(type) !== -1
        },
      },
    },
    watch: {
      innerValue (newVal) {
        debounce(() => {
          this.$emit('input', {
            name: this.name,
            value: newVal,
          })
        }, 500)
      },
    },
  }
</script>
