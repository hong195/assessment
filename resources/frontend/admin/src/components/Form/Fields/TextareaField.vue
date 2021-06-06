<template>
  <validation-provider
    v-slot="{ errors, required, ariaInput, ariaMsg }"
    tag="div"
    :rules="validationRule"
    :name="label"
    :vid="name"
  >
    <v-textarea
      v-model="innerValue"
      :label="label"
      :error-messages="errors"
    />
  </validation-provider>
</template>

<script>
  import FieldMixin from '@/components/Form/Mixins/FieldMixin'

  export default {
    name: 'TextArea',
    mixins: [FieldMixin],
  }
</script>

<style scoped>

</style>
