<template>
  <focus-trap v-model="isVisible__">
    <div
      ref="modal"
      v-show="isRootVisible"
      tabindex="0"
      @keyup.esc="handleEscapeKeyHit"
      :class="modalRootClass">
      <!-- TODO figure out nice close animation -->
      <transition
        enter-active-class="ease-out duration-300"
        enter-class="opacity-0"
        enter-to-class="opacity-100"
        leave-active-class="ease-in duration-200"
        leave-class="opacity-100"
        leave-to-class="opacity-0">
        <div
          v-show="isVisible__"
          class="fixed inset-0 transition-opacity"
          @click="handleBackgroundHit">
          <div class="absolute inset-0 bg-gray-500 opacity-75"/>
        </div>
      </transition>

      <transition
        v-if="showDefaultModalStyle"
        enter-active-class="ease-out duration-300"
        enter-class="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
        enter-to-class="opacity-100 translate-y-0 sm:scale-100"
        leave-active-class="ease-in duration-200"
        leave-class="opacity-100 translate-y-0 sm:scale-100"
        leave-to-class="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95">

        <div v-show="isVisible__" class="relative bg-white rounded-lg px-4 pt-5 pb-4 overflow-hidden shadow-xl transform transition-all sm:max-w-lg sm:w-full sm:p-6">
          <!-- Close button -->
          <slot name="close-button">
            <div class="hidden sm:block absolute top-0 right-0 pt-4 pr-4">
              <button @click="handleCloseButtonHit" type="button" class="text-gray-400 hover:text-gray-500 focus:outline-none focus:text-gray-500 transition ease-in-out duration-150">
                <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
              </button>
            </div>
          </slot>

          <!-- Some mainish content -->
          <slot name="main-content">
            <div class="sm:flex sm:items-start">
              <div :class="hintIconBackgroundColor" class="mx-auto flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full sm:mx-0 sm:h-10 sm:w-10">
                <slot name="hint-icon">
                  <svg class="h-6 w-6 text-red-600" fill="none" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" viewBox="0 0 24 24" stroke="currentColor"><path d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path><path d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                </slot>
              </div>
              <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left w-full">
                <h3 class="text-lg leading-6 font-medium text-gray-900">
                  {{ hintTitle }}
                </h3>
                <slot name="hint-body-section">
                  <div class="mt-2">
                    <p class="text-sm leading-5 text-gray-500" v-html="hintDescription">
                    </p>
                  </div>
                </slot>
              </div>
            </div>
          </slot>

          <!-- Some buttons -->
          <slot v-if="showBottomButtonGroup" name="bottom-button-group">
            <div class="mt-5 sm:mt-4 sm:flex sm:flex-row-reverse">
              <span class="flex w-full rounded-md shadow-sm sm:ml-3 sm:w-auto">
                <button
                  @click="handleMainActionButtonHit"
                  type="button"
                  :class="mainActionButtonColors"
                  class="inline-flex justify-center w-full rounded-md px-4 py-2 text-base leading-6 font-medium shadow-sm transition ease-in-out duration-150 sm:text-sm sm:leading-5">
                  {{ mainActionButtonText }}
                </button>
              </span>
              <span class="mt-3 flex w-full rounded-md shadow-sm sm:mt-0 sm:w-auto">
                <button @click="handleCancelActionButtonHit" type="button" class="inline-flex justify-center w-full rounded-md border border-gray-300 px-4 py-2 bg-white text-base leading-6 font-medium text-gray-700 shadow-sm hover:text-gray-500 focus:outline-none focus:border-blue-300 focus:shadow-outline transition ease-in-out duration-150 sm:text-sm sm:leading-5">
                  {{ cancelActionButtonText }}
                </button>
              </span>
            </div>
          </slot>
        </div>
      </transition>

      <slot v-if="!showDefaultModalStyle" name="alternative-modal" />
    </div>
  </focus-trap>
</template>

<script>
export default {
  props: {
    showDefaultModalStyle: { type: Boolean, default: true },
    modalRootClass: { type: String, default: 'fixed bottom-0 inset-x-0 px-4 pb-4 sm:inset-0 sm:flex sm:items-center sm:justify-center focus:outline-none' },
    isVisible: { type: Boolean, default: false },
    rootVisibleHideDelay: { type: Number, default: 300 },
    cancelActionButtonText: { type: String, default: 'Cancel' },
    mainActionButtonText: { type: String, default: 'Ok' },
    mainActionButtonColors: { type: String, default: 'border border-transparent bg-red-400 text-white hover:bg-red-300 focus:outline-none focus:border-red-500 focus:shadow-outline-red' },
    showBottomButtonGroup: { type: Boolean, default: true },
    hintIconBackgroundColor: { type: String, default: 'bg-red-100' },
    hintTitle: { type: String, default: 'Title' },
    hintDescription: { type: String, default: 'This is some description of what is happening.' },
    onBackgroundHit: { type: Function },
    onCloseButtonHit: { type: Function },
    onCancelActionButtonHit: { type: Function },
    onMainActionButtonHit: { type: Function },
    onEscapeKeyHit: { type: Function },
  },
  data: () => ({
    isRootVisible: false,
    isVisible__: false
  }),
  computed: {
    handleBackgroundHit () {
      if (typeof this.onBackgroundHit === 'function') {
        return this.onBackgroundHit.bind(this)
      }

      return this.defaultHandleBackgroundHit
    },
    handleCloseButtonHit () {
      if (typeof this.onCloseButtonHit === 'function') {
        return this.onCloseButtonHit.bind(this)
      }

      return this.defaultHandleCloseButtonHit
    },
    handleCancelActionButtonHit () {
      if (typeof this.onCancelActionButtonHit === 'function') {
        return this.onCancelActionButtonHit.bind(this)
      }

      return this.defaultHandleCancelActionButtonHit
    },
    handleMainActionButtonHit () {
      if (typeof this.onMainActionButtonHit === 'function') {
        return this.onMainActionButtonHit.bind(this)
      }

      return this.defaultHandleMainActionButtonHit
    },
    handleEscapeKeyHit () {
      if (typeof this.onEscapeKeyHit === 'function') {
        return this.onEscapeKeyHit.bind(this)
      }

      return this.defaultEscapeKeyHit
    },
  },
  watch: {
    isVisible__ (value) {
      this.$emit('update:isVisible', value)

      if (value) {
        this.isRootVisible = true

        // Allow escape key handling to work
        this.$nextTick(() => {
          this.$refs.modal.focus()
        })

        this.$emit('opened')

        return
      }

      // So we can get the nice out transition
      setTimeout(() => this.isRootVisible = false, this.rootVisibleHideDelay)
    },
    isVisible: {
      immediate: true,
      handler (value) {
        this.isVisible__ = value

        // if (value) {
        //   this.isRootVisible = true

        //   // Allow escape key handling to work
        //   this.$nextTick(() => {
        //     this.$refs.modal.focus()
        //   })

        //   this.$emit('opened')

        //   return
        // }

        // // So we can get the nice out transition
        // setTimeout(() => this.isRootVisible = false, this.rootVisibleHideDelay)
      }
    },
  },
  methods: {
    defaultHandleBackgroundHit () {
      this.isVisible__ = false
    },
    defaultHandleCloseButtonHit () {
      this.isVisible__ = false
    },
    defaultHandleCancelActionButtonHit () {
      this.isVisible__ = false
    },
    defaultHandleMainActionButtonHit () {
      this.isVisible__ = false
    },
    defaultEscapeKeyHit () {
      // For some reason i'm getting 🤷‍♂️ [Vue warn]: Avoid mutating a prop directly
      this.isVisible__ = false
    }
  }
}
</script>
