<template>
  <validation-provider
    v-slot="{ errors }"
    tag="div"
    :rules="validationRule"
    :name="label"
    :vid="name"
  >
    <v-subheader
      v-if="label"
      class="display-1"
    >
      {{ label }}
    </v-subheader>
    <v-row class="checkbox-component">
      <v-col
        v-if="!options.length"
      >
        1
      </v-col>
      <v-col
        v-for="(option, index) in options"
        v-else
        :key="`checkbox-${id}-${index}`"
      >
        <v-checkbox
          v-model="innerValue"
          :error-messages="errors"
          :value="option.id"
          :label="label"
        />
      </v-col>
    </v-row>
    <div
      v-show="errors.length"
      class="v-messages theme--light error--text"
      role="alert"
    >
      <div class="v-messages__wrapper">
        <div
          class="v-messages__message"
        >
          {{ errors[0] }}
        </div>
      </div>
    </div>
  </validation-provider>
</template>

<script>
  import FieldMixin from '@/components/Form/Mixins/FieldMixin'
  export default {
    name: 'CheckBox',
    mixins: [FieldMixin],
    props: {
      options: {
        type: Array,
        default: () => [],
      },
    },
    data: () => ({
      id: null,
    }),
    created () {
      this.id = this._uid
    },
  }
</script>

<style lang="scss">
.checkbox-component {
  .v-messages {
    display: none !important;
  }
}
</style>
