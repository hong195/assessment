import Vue from 'vue'
import App from '@/components/App.vue'
import store from './store'
import router from './router'
import './plugins/base'
import './plugins/vee-validate'
import './plugins/axios/index'
import vuetify from './plugins/vuetify'
import i18n from './plugins/i18n'
import { mapMutations } from 'vuex'

Vue.config.productionTip = false

function boot () {
  new Vue({
    created () {
      this.removePreloader()
    },
    methods: {
      ...mapMutations(['removePreloader']),
    },
    router,
    store,
    vuetify,
    i18n,
    render: h => h(App),
  }).$mount('#app')
}
// extract user before vue instance created
store.dispatch('user/fetchUser')
  .then(() => {
    boot()
  })
  .catch(() => {
    boot()
  })
