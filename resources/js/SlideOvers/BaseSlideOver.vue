<template>
  <div
    ref="slideOver"
    v-show="isRootVisible"
    :class="{ 'max-w-lg': !withBackgroundOverlay }"
    class="fixed w-full overflow-hidden bottom-0 right-0 top-0">
    <div class="absolute inset-0 overflow-hidden">

      <transition
        v-if="withBackgroundOverlay"
        enter-active-class="ease-in-out duration-500"
        enter-class="opacity-0"
        enter-to-class="opacity-100"
        leave-active-class="ease-in-out duration-500"
        leave-class="opacity-100"
        leave-to-class="opacity-0">
        <div
          v-show="isVisible"
          @click="handleBackgroundOverlayHit"
          class="absolute inset-0 bg-gray-500 bg-opacity-75 transition-opacity"/>
      </transition>

      <section class="absolute inset-y-0 pl-16 max-w-full right-0 flex">
        <transition
          enter-active-class="transform transition ease-in-out duration-500 sm:duration-700"
          enter-class="translate-x-full"
          enter-to-class="translate-x-0"
          leave-active-class="transform transition ease-in-out duration-500 sm:duration-700"
          leave-class="translate-x-0"
          leave-to-class="translate-x-full">

          <div v-show="isVisible" :class="paddingClass" class="relative w-screen max-w-md">

            <div :class="roundedClass" class="h-full divide-y divide-gray-200 flex flex-col bg-white shadow-xl">
              <div :class="roundedClass" class="flex-1 h-0 overflow-y-auto">
                <slot name="header" v-bind="{ handleCloseButtonHit }">
                  <header class="space-y-1 py-6 px-4 bg-indigo-700 sm:px-6">
                    <div class="flex items-center justify-between space-x-3">
                      <h2 v-html="headerTitle" class="text-lg leading-7 font-medium text-white"/>
                      <div class="h-7 flex items-center">
                        <button @click="handleCloseButtonHit" aria-label="Close panel" class="text-indigo-200 hover:text-white transition ease-in-out duration-150">
                          <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                          </svg>
                        </button>
                      </div>
                    </div>
                    <div>
                      <p v-html="headerDescription" class="text-sm leading-5 text-indigo-300"/>
                    </div>
                  </header>
                </slot>

                <slot name="body"/>

              </div>
              <div v-if="showStickyFooter" class="flex-shrink-0 px-4 py-4 space-x-4 flex justify-end">
                <slot name="footer"/>
<!--                 <span class="inline-flex rounded-md shadow-sm">
                  <button type="button" class="py-2 px-4 border border-gray-300 rounded-md text-sm leading-5 font-medium text-gray-700 hover:text-gray-500 focus:outline-none focus:border-blue-300 focus:shadow-outline-blue active:bg-gray-50 active:text-gray-800 transition duration-150 ease-in-out">
                    Cancel
                  </button>
                </span> -->
<!--                 <span class="inline-flex rounded-md shadow-sm">
                  <button type="submit" class="inline-flex justify-center py-2 px-4 border border-transparent text-sm leading-5 font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-500 focus:outline-none focus:border-indigo-700 focus:shadow-outline-indigo active:bg-indigo-700 transition duration-150 ease-in-out">
                    Save
                  </button>
                </span> -->
              </div>
            </div>
          </div>
        </transition>
      </section>
    </div>
  </div>
</template>

<script>
export default {
  props: {
    isVisible: { type: Boolean, default: true },
    isRounded: { type: Boolean, default: false },
    withBackgroundOverlay: { type: Boolean, default: false },
    showStickyFooter: { type: Boolean, default: true },
    isRoundedPaddingClass: { type: String, default: 'py-2 pr-2' },
    isRoundedRoundClass: { type: String, default: 'rounded-lg' },
    onCloseButtonHit: { type: Function },
    onBackgroundOverlayHit: { type: Function },
    headerTitle: { type: String },
    headerDescription: { type: String },
  },
  data: () => ({
    isRootVisible: false,
    isRootVisibleTimeout: null
  }),
  computed: {
    handleBackgroundOverlayHit () {
      if (typeof this.onBackgroundOverlayHit === 'function') {
        return this.onBackgroundOverlayHit.bind(this)
      }

      return this.defaultHandleBackgroundOverlayHit
    },
    handleCloseButtonHit () {
      if (typeof this.onCloseButtonHit === 'function') {
        return this.onCloseButtonHit.bind(this)
      }

      return this.defaultHandleCloseButtonHit
    },
    paddingClass () {
      return this.isRounded ? this.isRoundedPaddingClass : ''
    },
    roundedClass () {
      return this.isRounded ? this.isRoundedRoundClass : ''
    }
  },
  watch: {
    isVisible: {
      immediate: true,
      handler (value) {
        if (value) {
          this.clearRootVisibleTimout()
          this.isRootVisible = true

          // Allow escape key handling to work
          // this.$nextTick(() => {
          //   this.$refs.modal.focus()
          // })

          this.$emit('opened')

          return
        }

        // So we can get the nice out transition
        this.clearRootVisibleTimout()
        this.isRootVisibleTimeout = setTimeout(() => {
          this.isRootVisible = false
          this.isRootVisibleTimeout = null
        }, 700)
      }
    }
  },
  methods: {
    clearRootVisibleTimout () {
      if (this.isRootVisibleTimeout) {
        clearTimeout(this.isRootVisibleTimeout)
      }
    },
    defaultHandleBackgroundOverlayHit () {
      this.$emit('update:isVisible', false)
    },
    defaultHandleCloseButtonHit () {
      this.$emit('update:isVisible', false)
    },
  }
}
</script>
