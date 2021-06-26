<template>
  <validation-observer ref="obs" v-slot="{ handleSubmit }">
    <v-form :data-vv-scope="scope" :disabled="disabled" @submit.prevent="handleSubmit(onSubmit)">
      <v-row>
        <v-col
          v-for="(field, index) in schema"
          :key="index"
          :md="field.attributes.cols || 12"
          :lg="field.attributes.cols || 12"
          :sm="field.attributes.cols || 12"
          :cols="12"
        >
          <slot :name="`${field.name}-field`" :field="field" :updateFieldValue="updateFieldValue">
            <component
              :is="`${field.component}-field`"
              :scope="scope"
              :type="field.type"
              :name="field.name"
              :label="field.label"
              :placeholder="field.placeholder"
              :hint="field.hint"
              :value="field.value"
              :validation-rule="field.rule"
              :options="field.options"
              :attributes="field.attributes"
              @input="updateFieldValue"
            />
          </slot>
        </v-col>
      </v-row>
      <slot :loading="loading" :disabled="disabled" name="actions" :submitText="submitText">
        <v-card-actions align="center" class="pa-0 py-3">
          <v-btn
            :loading="loading"
            color="success"
            default
            large
            :disabled="disabled"
            type="submit"
          >
            {{ submitText }}
          </v-btn>
        </v-card-actions>
      </slot>
    </v-form>
  </validation-observer>
</template>

<script>
  import { ValidationObserver } from 'vee-validate'
  import TextField from './Fields/TextField'
  import CheckboxField from './Fields/CheckboxField'
  import RadioField from './Fields/RadioField'
  import SelectField from './Fields/SelectField'
  import TextareaField from './Fields/TextareaField'
  import TreeselectField from './Fields/TreeselectField'
  import FileField from './Fields/FileField'
  import DateField from './Fields/DateField'
  import FormActionMixin from './Mixins/FormActionsMixin'

  export default {
    name: 'FormBase',
    components: {
      TextField,
      CheckboxField,
      TextareaField,
      RadioField,
      SelectField,
      FileField,
      TreeselectField,
      ValidationObserver,
      DateField,
    },
    mixins: [FormActionMixin],
    props: {
      schema: {
        type: Array,
        default: () => [],
      },
      scope: {
        type: String,
        required: true,
      },
      loading: {
        type: Boolean,
        default: false,
      },
      disabled: {
        type: Boolean,
        default: false,
      },
      submitText: {
        type: String,
        default: 'Отправить',
      },
    },
    computed: {
      fieldsValue () {
        const values = {}
        this.schema.forEach((field) => {
          const name = field.name.split('.')
          this.assign(values, name, field.value)
        })
        return values
      },
    },
    watch: {
      disabled (val) {
        this.schema.forEach((field) => {
          if (field.attributes) {
            field.attributes.disabled = val
          } else {
            field.attribute = { disabled: val }
          }
        })
      },
    },
    created () {
      this.schema.forEach((field) => {
        const value = field.value !== undefined ? field.value : null
        this.$set(field, 'value', value)
      })
    },
    methods: {
      assign (obj, keyPath, value) {
        const lastKeyIndex = keyPath.length - 1
        for (var i = 0; i < lastKeyIndex; ++i) {
          const key = keyPath[i]
          if (!(key in obj)) {
            obj[key] = {}
          }
          obj = obj[key]
        }
        obj[keyPath[lastKeyIndex]] = value
      },
      getFieldByName (fieldName) {
        return Object.values(this.schema).find((el) => el.name === fieldName)
      },
      updateFieldValue (fieldData) {
        this.setFieldValue(fieldData)
        this.$emit('input', this.fieldsValue)
      },
      setFieldValue ({ name, value }) {
        const field = this.getFieldByName(name)
        field.value = value
      },
      async reset () {
        this.schema.forEach((field) =>
          this.setFieldValue({ name: field.name, value: null }),
        )
        requestAnimationFrame(() => {
          this.$refs.obs.reset()
          this.$emit('input', this.fieldsValue)
        })
      },
    },
  }
</script>
<style lang="scss">
.v-subheader {
  &.display-1 {
    padding-left: 0 !important;
  }
}
</style>
