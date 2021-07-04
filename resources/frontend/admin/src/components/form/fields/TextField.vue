<template>
  <validation-provider
    v-slot="{ errors, required, ariaInput, ariaMsg }"
    tag="div"
    :rules="validationRule"
    :name="label"
    :vid="name"
    :class="{ 'd-none': type === 'hidden'}"
  >
    <v-text-field
      :value="value"
      :name="name"
      :error-messages="errors"
      :type="type"
      v-bind="attributes"
      :label="label"
      :hint="hint"
      :placeholder="placeholder"
      @input="updateValue"
    />
  </validation-provider>
</template>

<script>

  import FieldMixin from '@/components/form/mixins/FieldMixin'
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
    methods: {
      updateValue: debounce(function (e) {
        this.$emit('input', {
          name: this.name,
          value: e,
        })
      }, 1000),
    },
  }
</script>
