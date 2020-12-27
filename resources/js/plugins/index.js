import filters from './filters'
import TailablePagination from 'tailable-pagination'
import VWave from 'v-wave'

export default function (Vue) {
    // External plugins.
    // Vue.use(require('vue-unique-id').default)
    Vue.use(TailablePagination)
    Vue.use(VWave)

    // Internal plugins.
    // Vue.use(require('./register-components').default)
    // Vue.use(require('./stores').default)
    Vue.use(filters)
}
