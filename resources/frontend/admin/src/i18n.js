import Vue from 'vue'
import VueI18n from 'vue-i18n'

import ru from 'vuetify/lib/locale/ru'
import en from 'vuetify/lib/locale/en'

Vue.use(VueI18n)

const messages = {
  ru: {
    ...require('@/locales/ru.json'),
    $vuetify: ru,
  },
  en: {
    ...require('@/locales/ru.json'),
    $vuetify: en,
  },
}

export default new VueI18n({
  locale: process.env.VUE_APP_I18N_LOCALE || 'ru',
  fallbackLocale: process.env.VUE_APP_I18N_FALLBACK_LOCALE || 'ru',
  messages,
})
