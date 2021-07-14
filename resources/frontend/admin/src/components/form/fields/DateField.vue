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
      :locale="locale"
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
  export default {
    name: 'DateField',
    mixins: [FieldMixin],
    data: () => ({
      date: (new Date(Date.now() - (new Date()).getTimezoneOffset() * 60000)).toISOString().substr(0, 10),
      menu: false,
      maxDate: moment().format('YYYY-MM-DD'),
      minDate: '2019-01-01',
    }),
    computed: {
      locale () {
        return process.env.VUE_APP_I18N_LOCALE || process.env.VUE_APP_I18N_FALLBACK_LOCALE
      },
    },
    methods: {
      change (date) {
        this.updateValue(date)
      },
    },
  }
</script>
