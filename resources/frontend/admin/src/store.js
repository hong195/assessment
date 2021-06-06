import Vue from 'vue'
import Vuex from 'vuex'
import user from './store_modules/user'
import ui from './store_modules/ui'
// eslint-disable-next-line camelcase
import alert_message from './store_modules/alert_message'
import pharmacy from '@/store_modules/pharmacy'
Vue.use(Vuex)

export default new Vuex.Store({
  modules: {
    ui, alert_message, user, pharmacy,
  },
})
