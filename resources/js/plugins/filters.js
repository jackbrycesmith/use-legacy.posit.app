import { initials } from '@/utils/strings'

export default {
    install (Vue) {
        Vue.filter('initials', initials)
    }
}
