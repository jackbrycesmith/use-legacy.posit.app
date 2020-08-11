import filters from './filters'

export default function (Vue) {
    // External plugins.
    // Vue.use(require('vue-unique-id').default)

    // Internal plugins.
    // Vue.use(require('./register-components').default)
    // Vue.use(require('./stores').default)
    Vue.use(filters)
}
