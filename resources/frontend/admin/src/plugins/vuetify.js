import '@mdi/font/css/materialdesignicons.css'
import Vue from 'vue'
import Vuetify from 'vuetify/lib'
import ru from 'vuetify/lib/locale/ru'
import DatetimePicker from 'vuetify-datetime-picker'

Vue.use(Vuetify)
Vue.use(DatetimePicker)

const theme = {
  primary: '#2f8cff',
  secondary: '#9C27b0',
  accent: '#9C27b0',
  info: '#00CAE3',
}

export default new Vuetify({
  lang: {
    // t: (key, ...params) => i18n.t(key, params),
    locales: { ru },
    // current: localStorage.getItem('locale') || process.env.VUE_APP_I18N_LOCALE,
    current: 'ru',
  },
  theme: {
    options: {
      customProperties: true,
    },
    themes: {
      dark: theme,
      light: theme,
    },
  },
})
