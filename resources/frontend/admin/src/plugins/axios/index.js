import Vue from 'vue'
import axios from 'axios'
import VueAxios from 'vue-axios'
require('./interceptors')

axios.defaults.baseURL = process.env.VUE_APP_API_ENDPOINT

Vue.use(VueAxios, axios)
