<template>
  <validation-provider
    v-slot="{ errors, required, ariaInput, ariaMsg }"
    tag="div"
    :rules="validationRule"
    :name="label"
    :vid="name"
  >
    <v-textarea
      :value="value"
      :label="label"
      :error-messages="errors"
      @input="updateValue"
    />
  </validation-provider>
</template>

<script>
  import FieldMixin from '../Mixins/FieldMixin'

  export default {
    name: 'TextArea',
    mixins: [FieldMixin],
  }
</script>

<style scoped>

</style>
