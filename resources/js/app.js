require('./bootstrap')

import { App as InertiaApp, plugin as InertiaPlugin } from '@inertiajs/inertia-vue'
import { FocusTrap } from 'focus-trap-vue'
import PortalVue from 'portal-vue'
import Fragment from 'vue-fragment'
import VueMeta from 'vue-meta'
import Vue from 'vue'
import store from '@/store'
import axios from 'axios'
import vClickOutside from 'v-click-outside'
import { inspect } from "@xstate/inspect"
import { InertiaForm } from 'laravel-jetstream'
import { InertiaProgress } from '@inertiajs/progress'

InertiaProgress.init({
  // The delay after which the progress bar will
  // appear during navigation, in milliseconds.
  delay: 250,

  // The color of the progress bar.
  color: '#FFC629',

  // Whether to include the default NProgress styles.
  includeCSS: true,

  // Whether the NProgress spinner will be shown.
  showSpinner: false,
})

// Plugins.
import registerPlugins from '@/plugins'
registerPlugins(Vue)

Vue.use(InertiaPlugin)
Vue.use(PortalVue)
Vue.use(InertiaForm)
Vue.use(VueMeta)
Vue.use(vClickOutside)
Vue.use(Fragment.Plugin)
Vue.component('FocusTrap', FocusTrap)
Vue.prototype.$route = (...args) => route(...args).url()
Vue.prototype.$http = axios.create()

const isDev = process.env.NODE_ENV !== "production"
Vue.config.performance = isDev

const app = document.getElementById('app')

if (isDev) {
  // xstate devtools...
  // inspect({
  //   iframe: false
  // })
}

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
