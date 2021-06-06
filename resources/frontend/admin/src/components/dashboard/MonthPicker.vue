<template>
  <v-menu
    ref="menu"
    v-model="menu"
    :close-on-content-click="false"
    :return-value.sync="date"
    transition="scale-transition"
    offset-y
    max-width="290px"
    min-width="290px"
  >
    <template v-slot:activator="{ on, attrs }">
      <v-text-field
        v-model="date"
        label="Год, Месяц"
        hide-details
        prepend-inner-icon="mdi-calendar"
        readonly
        outlined
        v-bind="attrs"
        v-on="on"
      />
    </template>
    <v-date-picker
      v-model.lazy="date"
      type="month"
      :scrollable="false"
      :locale="$i18n.locale"
    >
      <v-spacer />
      <v-btn
        text
        color="primary"
        @click="menu = false"
      >
        Отмена
      </v-btn>
      <v-btn
        text
        color="primary"
        @click="setDate(date, $refs.menu)"
      >
        Выбрать
      </v-btn>
    </v-date-picker>
  </v-menu>
</template>
<script>
  import moment from 'moment'
  export default {
    name: 'MonthPicker',
    data () {
      return {
        date: null,
        menu: false,
      }
    },
    created () {
      this.date = moment().format('YYYY-M')
      this.$emit('input', this.date)
    },
    methods: {
      setDate (date, dialog) {
        dialog.save(date)
        this.date = date
        this.$emit('input', this.date)
      },
    },
  }
</script>
