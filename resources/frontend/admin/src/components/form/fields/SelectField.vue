<template>
  <validation-provider
    v-slot="{ errors }"
    tag="div"
    :rules="validationRule"
    :name="label"
    :vid="name"
  >
    <v-select
      :value="value"
      :error-messages="errors"
      :items="options"
      v-bind="attributes"
      item-text="name"
      item-value="id"
      :label="label"
      @change="updateValue"
    />
  </validation-provider>
</template>

<script>
  import FieldMixin from '@/components/form/mixins/FieldMixin'
  export default {
    name: 'SelectField',
    mixins: [FieldMixin],
    props: {
      options: {
        type: Array,
        default: () => [],
      },
    },
    watch: {
      value (val) {
        this.updateValue(val)
      },
    },
  }
</script>

<style scoped>

</style>
