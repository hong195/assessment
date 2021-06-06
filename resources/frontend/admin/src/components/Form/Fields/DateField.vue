<template>
  <div>
    <v-datetime-picker
      v-if="attributes.timepicker"
      v-model="innerValue"
      :label="label"
      :v-bing="attributes"
      outlined
      time-format="HH:mm"
      :text-field-props="{outlined: true, 'prepend-inner-icon': 'mdi-calendar'}"
      :date-picker-props="{...attributes}"
      :time-picker-props="{
        format: '24hr'
      }"
    >
      <template slot="dateIcon">
        <v-icon>mdi-calendar</v-icon>
      </template>
      <template slot="timeIcon">
        <v-icon>mdi-clock</v-icon>
      </template>
      <template slot="actions" slot-scope="{ parent }">
        <v-btn color="error lighten-1" @click.native="parent.clearHandler">
          Очистить
        </v-btn>
        <v-btn color="success darken-1" @click="parent.okHandler">
          Ок
        </v-btn>
      </template>
    </v-datetime-picker>
    <v-menu
      v-else
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
            v-model="innerValue"
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
      <v-datetime-picker
        v-if="attributes.timepicker"
        v-model="innerValue"
      />
      <v-date-picker
        v-else
        ref="picker"
        v-model="innerValue"
        v-bind="attributes"
        :locale="locale"
        @change="save"
      />
    </v-menu>
  </div>
</template>
<script>
  import FieldMixin from '@/components/Form/Mixins/FieldMixin'
  import moment from 'moment'
  export default {
    name: 'DateField',
    mixins: [FieldMixin],
    props: {
      value: {
        type: [String, Number, Object, Array, Date],
        required: false,
      },
    },
    data: () => ({
      date: null,
      menu: false,
    }),
    computed: {
      locale () {
        return process.env.VUE_APP_I18N_LOCALE || process.env.VUE_APP_I18N_FALLBACK_LOCALE
      },
    },
    watch: {
      menu (val) {
        val && setTimeout(() => (this.$refs.picker.activePicker = 'YEAR'))
      },
    },
    methods: {
      save (date) {
        this.$refs.menu.save(date)
      },
    },
  }
</script>
