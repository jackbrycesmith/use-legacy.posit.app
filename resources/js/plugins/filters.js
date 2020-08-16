import { initials, titleCase } from '@/utils/strings'

export default {
    install (Vue) {
        Vue.filter('initials', initials)
        Vue.filter('titleCase', titleCase)
    }
}
