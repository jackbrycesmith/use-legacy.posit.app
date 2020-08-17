<template>
  <div class="relative">
    <button
      ref="triggerButton"
      type="button"
      :class="{ 'animate-bounce': shouldAnimateBounce }"
      class="inline-flex h-10 w-10 items-center justify-center rounded-full bg-white shadow-md text-gray-400 focus:outline-none focus:text-gray-500 hover:text-gray-500 transition ease-in-out duration-150"
      title="Add proposal recipient"
      aria-label="Add proposal recipient" aria-haspopup="true" :aria-expanded="isOpen"
      @click="handleSelectButtonClick">
      <svg class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
        <path fill-rule="evenodd" d="M10 3a1 1 0 011 1v5h5a1 1 0 110 2h-5v5a1 1 0 11-2 0v-5H4a1 1 0 110-2h5V4a1 1 0 011-1z" clip-rule="evenodd" />
      </svg>
    </button>

    <!-- Dropdown -->
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
        class="w-52 sm:w-60 z-10 origin-top absolute right-0 mt-1 rounded-md shadow-lg">
        <div class="rounded-md bg-white shadow-xs">
          <div class="py-1">

            <span class="px-2 py-2 font-semibold text-xs">Add new or existing contact</span>

            <div class="flex rounded-md shadow-sm mx-1.5 mb-1">
              <div class="relative flex-grow focus-within:z-10">
                <input
                  ref="input"
                  class="form-input border-gray-200 shadow-inner block w-full rounded-none rounded-l-md transition ease-in-out duration-150 sm:text-sm sm:leading-5 focus:shadow-inner"
                  placeholder="John Doe">
              </div>
              <button
                class="-ml-px relative inline-flex items-center px-4 py-2 border border-gray-200 shadow-inner text-sm leading-5 font-medium rounded-r-md text-gray-700 bg-gray-50 hover:text-gray-500 hover:bg-white focus:outline-none focus:shadow-inner focus:border-blue-300 active:bg-gray-100 active:text-gray-700 transition ease-in-out duration-150"
                @blur="handleAddButtonBlur"
                >
                <svg viewBox="0 0 20 20" fill="currentColor" class="h-5 w-5 text-gray-400"><path d="M8 9a3 3 0 100-6 3 3 0 000 6zM8 11a6 6 0 016 6H2a6 6 0 016-6zM16 7a1 1 0 10-2 0v1h-1a1 1 0 100 2h1v1a1 1 0 102 0v-1h1a1 1 0 100-2h-1V7z"></path></svg>
                <span class="ml-2">Add</span>
              </button>
            </div>

            <!-- Existing Org Contacts... -->
            <ul
              ref="existingContactsList"
              role="listbox"
              class="h-48">

              <li
                tabindex="1"
                class="block px-4 py-2 text-sm leading-5 text-gray-700 hover:bg-gray-100 hover:text-gray-900 focus:outline-none focus:bg-gray-100 focus:text-gray-900" role="option"
              >
                Recipient Name
              </li>
            </ul>

<!--             <a href="#" class="block px-4 py-2 text-sm leading-5 text-gray-700 hover:bg-gray-100 hover:text-gray-900 focus:outline-none focus:bg-gray-100 focus:text-gray-900" role="menuitem">Org ✔️</a> -->
          </div>
        </div>
      </div>
    </transition>
  </div>
</template>

<script>
export default {
  data () {
    return {
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
  computed: {
    shouldAnimateBounce () {
      return !this.isOpen // TODO take into account if a recipient has already been selected...
    }
  },
  methods: {
    handleSelectButtonClick () {
      this.isOpen = !this.isOpen
    },
    onClickOutside (event) {
      if (this.isOpen) {
        this.isOpen = false
      }
    },
    determineWhetherClickOutside (event) {
      console.log('determineWhetherClickOutside: ', event)
      return event.target.parentElement !== this.$refs.triggerButton
    },
    handleAddButtonBlur (event) {
      this.$refs.existingContactsList?.firstElementChild?.focus()
    }
  },
  watch: {
    isOpen: {
      handler (value) {
        if (value) {
          this.$nextTick(() => {
            this.$refs.input?.focus()
          })
        }
      }
    }
  }
}
</script>
