<template>
  <validation-observer ref="obs" v-slot="{ handleSubmit }">
    <v-form :data-vv-scope="scope"
            :disabled="disabled"
            @submit.prevent="submit"
    >
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
  import TextField from './fields/TextField'
  import CheckboxField from './fields/CheckboxField'
  import RadioField from './fields/RadioField'
  import SelectField from './fields/SelectField'
  import TextareaField from './fields/TextareaField'
  import TreeselectField from './fields/TreeselectField'
  import FileField from './fields/FileField'
  import DateField from './fields/DateField'
  import FormActionMixin from '@/components/form/mixins/FormActionsMixin'

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
      schema (schema) {
        const values = {}
        schema.forEach((field) => {
          const name = field.name.split('.')
          this.assign(values, name, field.value)
        })
        this.$emit('input', this.fieldsValue())
      },
    },
    created () {
      this.schema.forEach((field) => {
        const value = field.value !== undefined ? field.value : null
        this.$set(field, 'value', value)
      })
    },
    methods: {
      fieldsValue () {
        const values = {}
        this.schema.forEach((field) => {
          const name = field.name.split('.')
          this.assign(values, name, field.value)
        })
        return values
      },
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
        this.$emit('input', this.fieldsValue())
      },
      setFieldValue ({ name, value }) {
        const field = this.getFieldByName(name)
        field.value = value
      },
      async reset () {
        this.schema.forEach((field) => {
          if (field.type !== 'hidden') {
            this.setFieldValue({ name: field.name, value: null })
          }
        })
        requestAnimationFrame(() => {
          this.$refs.obs.reset()
          this.$emit('input', this.fieldsValue())
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
