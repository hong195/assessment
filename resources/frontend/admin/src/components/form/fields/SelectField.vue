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
      :item-text="fieldLabel"
      :item-value="fieldValue"
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
    computed: {
      fieldLabel () {
        return this.attributes['item-text'] ? this.attributes['item-text'] : 'name'
      },
      fieldValue () {
        return this.attributes['item-value'] ? this.attributes['item-value'] : 'id'
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
