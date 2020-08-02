require('./bootstrap')

import { InertiaApp } from '@inertiajs/inertia-vue'
import { FocusTrap } from 'focus-trap-vue'
import PortalVue from 'portal-vue'
import Fragment from 'vue-fragment'
import VueMeta from 'vue-meta'
import Vue from 'vue'
import store from '@/store'
import axios from 'axios'
import FormInput from '@/Components/form-input'
import vClickOutside from 'v-click-outside'

Vue.use(InertiaApp)
Vue.use(PortalVue)
Vue.use(VueMeta)
Vue.use(vClickOutside)
Vue.use(Fragment.Plugin)
Vue.component('FocusTrap', FocusTrap)
Vue.component('form-input', FormInput) // TODO remove this auth scaffolding thing.
Vue.prototype.$route = (...args) => route(...args).url()
Vue.prototype.$http = axios.create()

const isDev = process.env.NODE_ENV !== "production"
Vue.config.performance = isDev

const app = document.getElementById('app')

new Vue({
  store,
  metaInfo: {
    titleTemplate: (title) => title ? `${title} – Posit.app` : 'Posit.app'
  },
  render: h => h(InertiaApp, {
    props: {
      initialPage: JSON.parse(app.dataset.page),
      resolveComponent: name => import(`@/Pages/${name}`).then(module => module.default)
    },
  }),
}).$mount(app)
