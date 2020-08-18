<template>
  <focus-trap v-model="isOpen">
    <div class="relative">
      <button
        ref="triggerButton"
        type="button"
        :class="{ 'animate-bounce': shouldAnimateBounce }"
        class="inline-flex h-10 w-10 items-center justify-center rounded-full bg-white shadow-md text-gray-400 focus:outline-none focus:text-gray-500 hover:text-gray-500 transition ease-in-out duration-150"
        title="Add proposal recipient"
        aria-label="Add proposal recipient" aria-haspopup="true" :aria-expanded="isOpen"
        @click="handleSelectButtonClick">
        <span v-if="proposal.has_recipient" :title="proposal.recipient_name" class="text-orange-400">
          {{ proposal.recipient_name | initials }}
        </span>

        <svg v-else class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
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
                    v-model="input"
                    class="form-input border-gray-200 shadow-inner block w-full rounded-none rounded-l-md transition ease-in-out duration-150 sm:text-sm sm:leading-5 focus:shadow-inner"
                    placeholder="John Doe">
                </div>
                <button
                  class="-ml-px relative inline-flex items-center px-4 py-2 border border-gray-200 shadow-inner text-sm leading-5 font-medium rounded-r-md text-gray-700 bg-gray-50 hover:text-gray-500 hover:bg-white focus:outline-none focus:shadow-inner focus:border-blue-300 active:bg-gray-100 active:text-gray-700 transition ease-in-out duration-150">
                  <svg viewBox="0 0 20 20" fill="currentColor" class="h-5 w-5 text-gray-400"><path d="M8 9a3 3 0 100-6 3 3 0 000 6zM8 11a6 6 0 016 6H2a6 6 0 016-6zM16 7a1 1 0 10-2 0v1h-1a1 1 0 100 2h1v1a1 1 0 102 0v-1h1a1 1 0 100-2h-1V7z"></path></svg>
                  <span class="ml-2">Add</span>
                </button>
              </div>

              <!-- Existing Org Contacts... -->
              <ul
                ref="existingContactsList"
                role="listbox"
                tabindex="-1"
                class="h-48 outline-none overflow-y-scroll">

                <li
                  v-for="(option, o) in filteredOptions"
                  :key="option.id"
                  :tabindex="o"
                  :aria-selected="isSelected(option)"
                  role="option"
                  class="relative block px-4 py-2 text-sm leading-5 text-gray-700 hover:bg-gray-100 hover:text-gray-900 focus:outline-none focus:bg-gray-100 focus:text-gray-900"
                  @click="selectOption(option)">

                  <div class="flex items-center space-x-3">

                    <div class="flex justify-center items-center bg-yellow-300 flex-shrink-0 h-7 w-7 rounded-full text-xs">
                      {{ option.name | initials }}
                    </div>
                    <!-- Selected: "font-semibold", Not Selected: "font-normal" -->
                    <span class="font-normal block truncate">
                      {{ option.name }}
                    </span>
                  </div>

                  <!--
                    Checkmark, only display for selected option.

                    Highlighted: "text-white", Not Highlighted: "text-indigo-600"
                  -->
                  <span v-if="isSelected(option)" class="absolute inset-y-0 right-0 flex items-center pr-4">
                    <svg class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                      <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd" />
                    </svg>
                  </span>

                </li>

              </ul>
            </div>
          </div>
        </div>
      </transition>
    </div>
  </focus-trap>
</template>

<script>
import { filter, set } from 'lodash-es'

export default {
  props: {
    options: { type: Array, default: () => [] },
    proposal: { type: Object },
    filterSearchValue: { type: String, default: 'name' },
    canBounce: { type: Boolean, default: true }
  },
  data () {
    return {
      isOpen: false,
      input: '',
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
      return this.canBounce && !this.isOpen && !this.proposal.has_recipient
    },
    filteredOptions () {
      const query = this.input

      return filter(this.options, (option) => {
        return option[this.filterSearchValue].toLowerCase().indexOf(query.toLowerCase()) > -1
      })
    },
    selectedOptionId () {
      return this.proposal?.recipient?.id
    }
  },
  methods: {
    isSelected (option) {
      return this.selectedOptionId === option.id
    },
    handleSelectButtonClick () {
      this.isOpen = !this.isOpen
    },
    onClickOutside (event) {
      if (this.isOpen) {
        this.isOpen = false
      }
    },
    selectOption (option) {
      const updatedProposal = set(this.proposal, 'recipient', option)
      this.$emit('update:proposal', updatedProposal)
      this.isOpen = false
      this.$refs.triggerButton?.focus()
    }
  },
  watch: {
    isOpen: {
      handler (value) {
        if (value) {
          this.$nextTick(() => {
            this.$refs.input?.focus()
          })
        } else {
          this.input = ''
        }
      }
    }
  }
}
</script>
