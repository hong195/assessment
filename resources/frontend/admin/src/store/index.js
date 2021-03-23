import Vue from 'vue'
import Vuex from 'vuex'
import user from './user'
import ui from './ui'
import alert from './alert'

Vue.use(Vuex)

export default new Vuex.Store({
  strict: true,
  modules: {
    ui, alert, user,
  },
})
