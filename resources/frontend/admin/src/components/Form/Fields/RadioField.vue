<template>
  <v-row>
    <validation-provider
      v-slot="{ errors }"
      tag="div"
      :rules="validationRule"
      :name="label"
      :vid="name"
    >
      <v-col
        v-if="label"
        cols="12"
      >
        <v-subheader
          class="display-1 mt-1"
        >
          {{ label }}
        </v-subheader>
      </v-col>
      <v-radio-group
        v-model="innerValue"
        class="ml-4 mt-0"
        :error-messages="errors"
      >
        <v-radio
          v-for="(option, index) in options"
          :key="`checkbox-${id}-${index}`"
          :label="option.label"
          :name="option.name"
          :value="option.value"
        />
      </v-radio-group>
    </validation-provider>
  </v-row>
</template>

<script>
  import FieldMixin from '@/components/Form/Mixins/FieldMixin'

  export default {
    name: 'Radio',
    mixins: [FieldMixin],
    props: {
      options: {
        type: Array,
        default: () => [],
      },
    },
    created () {
      this.id = this._uid
    },
  }
</script>
