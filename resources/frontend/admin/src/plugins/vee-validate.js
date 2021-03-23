import Vue from 'vue'
import { ValidationProvider, localize, extend } from 'vee-validate'
import * as rules from 'vee-validate/dist/rules'
import ru from 'vee-validate/dist/locale/ru.json'
Object.keys(rules).forEach(rule => {
  extend(rule, rules[rule])
})
localize('ru', ru)

Vue.component('ValidationProvider', ValidationProvider)
