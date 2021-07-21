<template>
  <v-menu
    ref="menu"
    v-model="menu"
    :close-on-content-click="false"
    transition="scale-transition"
    offset-y
    min-width="290px"
  >
    <template v-slot:activator="{ on, attrs }">
      <validation-provider
        v-slot="{ errors }"
        :rules="validationRule"
        tag="div"
        :name="label"
        :vid="name"
      >
        <v-text-field
          :value="value"
          :name="name"
          v-bind="attributes"
          :error-messages="errors"
          :label="label"
          prepend-inner-icon="mdi-calendar"
          readonly
          v-on="on"
        />
      </validation-provider>
    </template>
    <v-date-picker
      ref="picker"
      v-model="date"
      v-bind="attributes"
      :max="maxDate"
      :min="minDate"
      @change="change"
    >
      <v-btn
        text
        color="primary"
        @click="menu = false"
      >
        Cancel
      </v-btn>
      <v-btn
        text
        color="primary"
        @click="$refs.menu.save(date)"
      >
        OK
      </v-btn>
    </v-date-picker>
  </v-menu>
</template>
<script>
  import FieldMixin from '@/components/form/mixins/FieldMixin'
  import moment from 'moment'
  import _ from 'lodash'
  export default {
    name: 'DateField',
    mixins: [FieldMixin],
    data: () => ({
      date: null,
      menu: false,
      attrs: {},
    }),
    computed: {
      locale () {
        return 'ru'
      },
      maxDate () {
        if (_.isEmpty(this.attributes.max)) {
          return moment().format('YYYY-MM-DD')
        }

        return this.attributes.max
      },
      minDate () {
        if (_.isEmpty(this.attributes.min)) {
          return '2019-01-01'
        }
        return this.attributes.min
      },
    },
    watch: {
      value (value) {
        this.date = value
      },
    },
    mounted () {
      this.date = this.value || (new Date(Date.now() - (new Date()).getTimezoneOffset() * 60000)).toISOString().substr(0, 10)
    },
    methods: {
      change (date) {
        this.updateValue(date)
      },
    },
  }
</script>
