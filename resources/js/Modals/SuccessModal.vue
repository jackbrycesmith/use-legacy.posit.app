<template>
  <portal to="modal">
    <BaseModal
      ref="baseModal"
      :is-visible.sync="isVisible"
      :show-bottom-button-group="false">

      <template #main-content>
        <div class="sm:flex sm:items-start">

          <component :is="emojiComponent" class="h-16 w-16 mx-auto" />

          <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left w-full">
            <h3 class="text-lg leading-6 font-medium text-gray-900" v-html="title" />

            <div class="mt-2">
              <p class="text-sm leading-5 text-gray-500" v-html="description" />
            </div>
          </div>
        </div>
      </template>

    </BaseModal>
  </portal>
</template>

<script>
import BaseModal from '@/Modals/BaseModal'

// const asyncComponentTester = function(importPromise, latency){
//   return new Promise((resolve) => {
//     setTimeout(() => {
//       resolve(importPromise)
//     }, latency)
//   })
// }

const EMOJI_LOADING_PLACEHOLDER = `<div class="w-16 h-16"></div>`
const EMOJI_LOADING_PLACEHOLDER_DELAY = 0

const ThumbsUp = () => ({
  component: import('@/Icons/IconSensaEmojiThumbsUp'),
  delay: EMOJI_LOADING_PLACEHOLDER_DELAY,
  loading: {
    template: EMOJI_LOADING_PLACEHOLDER,
  },
})

const SmilingFaceWithHearts = () => ({
  component: import('@/Icons/IconSensaEmojiSmilingFaceWithHearts'),
  delay: EMOJI_LOADING_PLACEHOLDER_DELAY,
  loading: {
    template: EMOJI_LOADING_PLACEHOLDER,
  },
})

export default {
  name: "SuccessModal",
  components: {
    BaseModal,
    ThumbsUp,
    SmilingFaceWithHearts
  },
  data () {
    return {
      isVisible: false
    }
  },
  props: {
    emojiComponent: {
      type: String,
      default: 'ThumbsUp',
      validator: (val) => ['ThumbsUp', 'SmilingFaceWithHearts'].includes(val)
    },
    title: {
      type: String,
      default: 'Success'
    },
    description: {
      type: String,
      default: ''
    }
  },
  methods: {
    show () {
      this.isVisible = true
      return this
    },
  }
}
</script>
