<template>
  <validation-provider
    v-slot="{ errors }"
    tag="div"
    :rules="validationRule"
    :name="label"
    :vid="name"
  >
    <v-select
      v-model="innerValue"
      :error-messages="errors"
      :items="options"
      v-bind="attributes"
      item-text="name"
      item-value="id"
      :label="label"
    />
  </validation-provider>
</template>

<script>
  import FieldMixin from '@/components/Form/Mixins/FieldMixin'
  export default {
    name: 'SelectField',
    mixins: [FieldMixin],
    props: {
      options: {
        type: Array,
        default: () => [],
      },
    },
  }
</script>

<style scoped>

</style>
