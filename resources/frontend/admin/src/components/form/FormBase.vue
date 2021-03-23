<template>
  <validation-observer ref="obs" v-slot="{ handleSubmit }">
    <v-form :data-vv-scope="scope" @submit.prevent="handleSubmit(handle)">
      <v-row>
        <v-col
          v-for="(field, index) in schema"
          :key="index"
          :md="field.attributes.cols || 12"
        >
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
        </v-col>
      </v-row>
      <slot :loading="loading" name="actions">
        <v-card-actions align="center" class="pa-0 py-3">
          <v-btn
            :loading="loading"
            color="success"
            default
            large
            type="submit"
          >
            {{ buttonText }}
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
      method: {
        type: String,
        default: 'post',
        validator: (method) => {
          return ['post', 'get', 'put', 'delete'].includes(method.toLowerCase())
        },
      },
      scope: {
        type: String,
        required: true,
      },
      currentItem: {
        type: Object,
        default: () => {
          Promise.resolve()
        },
      },
    },
    data: () => ({
      loading: false,
    }),
    computed: {
      buttonText () {
        let text
        if (this.method === 'post') {
          text = 'Добавить'
        } else if (['put', 'patch'].includes(this.method)) {
          text = 'Обновить'
        }
        return text
      },
      fieldsValue () {
        const values = {}
        this.schema.forEach((field) => {
          const name = field.name.split('.')
          this.assign(values, name, field.value)
        })
        return values
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
