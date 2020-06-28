require('./bootstrap')

import { InertiaApp } from '@inertiajs/inertia-vue'
import { FocusTrap } from 'focus-trap-vue'
import PortalVue from 'portal-vue'
import Fragment from 'vue-fragment'
import VueMeta from 'vue-meta'
import Vue from 'vue'
import store from '@/store'

Vue.use(InertiaApp)
Vue.use(PortalVue)
Vue.use(VueMeta)
Vue.use(Fragment.Plugin)
Vue.component('FocusTrap', FocusTrap)
Vue.prototype.$route = (...args) => route(...args).url()

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
