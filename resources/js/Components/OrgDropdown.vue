<template>
  <!-- <div class="flex-1 flex flex-col overflow-y-auto"> -->
    <!-- User account dropdown -->
    <div class="sm:mt-6 relative text-left">
      <!-- Dropdown menu toggle, controlling the show/hide state of dropdown menu. -->
      <div>
        <button ref="menuTriggerButton" @click="handleDropdownButtonClick" type="button" class="group w-full rounded-md px-3.5 py-2 text-sm leading-5 font-medium text-gray-700 hover:bg-gray-50 hover:text-gray-500 focus:outline-none focus:bg-gray-200 focus:border-blue-300 active:bg-gray-50 active:text-gray-800 transition ease-in-out duration-150" id="options-menu" aria-haspopup="true" aria-expanded="true">
          <div class="flex w-full justify-between items-center">
            <div class="flex items-center justify-between space-x-3">
              <div class="w-10 h-10 bg-gray-300 rounded-full flex-shrink-0 flex items-center justify-center">
                {{ org__.avatar_letter_initial }}
              </div>
              <div class="flex-1">
                <h2 class="text-gray-900 text-sm leading-5 font-medium">
                  {{ org__.name }}
                </h2>
                <p class="text-gray-500 text-sm leading-5 truncate uppercase">org</p>
              </div>
            </div>
            <svg class="h-5 w-5 text-gray-400 group-hover:text-gray-500" viewBox="0 0 20 20" fill="currentColor">
              <path fill-rule="evenodd" d="M10 3a1 1 0 01.707.293l3 3a1 1 0 01-1.414 1.414L10 5.414 7.707 7.707a1 1 0 01-1.414-1.414l3-3A1 1 0 0110 3zm-3.707 9.293a1 1 0 011.414 0L10 14.586l2.293-2.293a1 1 0 011.414 1.414l-3 3a1 1 0 01-1.414 0l-3-3a1 1 0 010-1.414z" clip-rule="evenodd" />
            </svg>
          </div>
        </button>
      </div>

      <!-- Dropdown panel -->
      <transition
        enter-active-class="transition ease-out duration-100"
        enter-class="transform opacity-0 scale-95"
        enter-to-class="transform opacity-100 scale-100"
        leave-active-class="transition ease-in duration-75"
        leave-class="transform opacity-100 scale-100"
        leave-to-class="transform opacity-0 scale-95">
        <div
          v-click-outside="vcoConfig"
          v-if="isOpen"
          class="z-10 origin-top absolute right-0 left-0 mt-1 rounded-md shadow-lg">
          <div class="rounded-md bg-white shadow-xs" role="menu" aria-orientation="vertical" aria-labelledby="options-menu">
            <div class="py-1">
              <a href="#" class="block px-4 py-2 text-sm leading-5 text-gray-700 hover:bg-gray-100 hover:text-gray-900 focus:outline-none focus:bg-gray-100 focus:text-gray-900" role="menuitem">Org ✔️</a>
            </div>
            <div class="border-t border-gray-100"></div>
            <div class="py-1">
              <a href="#" class="block px-4 py-2 text-sm leading-5 text-gray-700 hover:bg-gray-100 hover:text-gray-900 focus:outline-none focus:bg-gray-100 focus:text-gray-900" role="menuitem">Create new team</a>
            </div>
          </div>
        </div>
      </transition>
    </div>
  <!-- </div> -->
</template>

<script>
import Organisation from '@/models/Organisation'

export default {
  props: {
    org: { type: Object, default: () => {} }
  },
  data () {
    return {
      org__: Organisation.make(),
      isOpen: false,
      vcoConfig: {
        handler: this.onClickOutside,
        middleware: this.determineWhetherClickOutside,
        events: ['dblclick', 'click'],
        // Note: The default value is true, but in case you want to activate / deactivate
        //       this directive dynamically use this attribute.
        isActive: true
      }
    }
  },
  watch: {
    org: {
      immediate: true,
      handler (value) {
        this.org__ = Organisation.make(value)
      }
    }
  },
  methods: {
    handleDropdownButtonClick () {
      this.isOpen = !this.isOpen
    },
    handleDropdownItemClick () {
      this.isOpen = false
    },
    onClickOutside (event) {
      if (this.isOpen) {
        this.isOpen = false
      }
    },
    determineWhetherClickOutside (event) {
      return event.target.parentElement !== this.$refs.menuTriggerButton
    }
  }
}
</script>
