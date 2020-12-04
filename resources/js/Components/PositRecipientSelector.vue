<template>
  <fragment>
    <template v-if="editable">
      <focus-trap v-model="isOpen">
        <div :class="fragmentItemClass" class="relative">
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
                        type="text"
                        v-model="input"
                        class="focus:ring-primary-yellow-500 focus:border-primary-yellow-500 block w-full rounded-none rounded-l-md sm:text-sm border-gray-300 shadow-inner"
                        placeholder="Enter Name…">
                    </div>
                    <button
                      class="-ml-px relative inline-flex items-center px-4 py-2 border border-gray-200 shadow-inner text-sm leading-5 font-medium rounded-r-md text-gray-700 bg-gray-50 hover:text-gray-500 hover:bg-white focus:outline-none focus:shadow-inner focus:border-blue-300 active:bg-gray-100 active:text-gray-700 focus:ring-1 focus:ring-primary-yellow-500 focus:border-primary-yellow-500"
                      :class="{ 'cursor-not-allowed': !canAddNewRecipient }"
                      :disabled="!canAddNewRecipient"
                      @click="handleAddNewRecipient">
                      <svg v-if="!isAdding" viewBox="0 0 20 20" fill="currentColor" class="h-5 w-5 text-gray-400"><path d="M8 9a3 3 0 100-6 3 3 0 000 6zM8 11a6 6 0 016 6H2a6 6 0 016-6zM16 7a1 1 0 10-2 0v1h-1a1 1 0 100 2h1v1a1 1 0 102 0v-1h1a1 1 0 100-2h-1V7z"></path></svg>
                      <svg v-else class="animate-spin h-5 w-5 text-gray-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                      </svg>
                      <span class="hidden sm:inline ml-2">Add</span>
                    </button>
                  </div>

                  <!-- Existing Org Contacts... -->
                  <ul
                    ref="existingContactsList"
                    role="listbox"
                    tabindex="-1"
                    class="h-48 outline-none overflow-y-scroll"
                    @keydown.enter="selectOptionEnterPressed"
                    >

                    <li
                      v-for="(option, o) in filteredOptions"
                      :key="option.id"
                      :tabindex="o"
                      :aria-selected="isSelected(option)"
                      role="option"
                      class="relative block px-4 py-2 text-sm leading-5 text-gray-700 hover:bg-gray-100 hover:text-gray-900 focus:outline-none focus:bg-gray-100 focus:text-gray-900"
                      @click="selectOption(option)"
                      @focus="handleOptionFocus(option)">

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

    <template v-else>
      <div :class="fragmentItemClass">
        <div
          class="inline-flex h-10 w-10 items-center justify-center rounded-full bg-white shadow-md text-gray-400 focus:outline-none focus:text-gray-500 hover:text-gray-500 transition ease-in-out duration-150"
          :title="proposal.recipient_name">
          <span v-if="proposal.has_recipient" class="text-orange-400 select-none">
            {{ proposal.recipient_name | initials }}
          </span>
        </div>
      </div>
    </template>

  </fragment>
</template>

<script>
import { filter, set, trim } from 'lodash-es'

export default {
  props: {
    options: { type: Array, default: () => [] },
    editable: { type: Boolean, default: true },
    proposal: { type: Object },
    filterSearchValue: { type: String, default: 'name' },
    fragmentItemClass: { type: String, default: '' },
    canBounce: { type: Boolean, default: true }
  },
  data () {
    return {
      isOpen: false,
      isAdding: false,
      focusedOption: null,
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
    canAddNewRecipient () {
      const hasInput = trim(this.input).length > 0
      return hasInput && !this.isAdding
    },
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
    },
  },
  methods: {
    isSelected (option) {
      return this.selectedOptionId === option.id
    },
    handleSelectButtonClick () {
      if (!this.editable) return
      this.isOpen = !this.isOpen
    },
    onClickOutside (event) {
      if (this.isOpen) {
        this.isOpen = false
      }
    },
    selectOptionEnterPressed (e) {
      e.preventDefault()
      if (this.isOpen && this.focusedOption) {
        this.selectOption(this.focusedOption)
      }
    },
    handleOptionFocus (option) {
      this.focusedOption = option
    },
    selectOption (option) {
      // TODO probably shouldn't be mutating this directly, but seems to be working fine
      const shouldUpdateRecipientOnServer = !(this.proposal.recipient?.id === option.id)
      const updatedPosit = set(this.proposal, 'recipient', option)
      this.$emit('update:proposal', updatedPosit)
      this.isOpen = false

      if (shouldUpdateRecipientOnServer) {
        this.proposal.updateRecipient()
      }
      this.$refs.triggerButton?.focus()
    },
    async handleAddNewRecipient () {
      try {
        this.isAdding = true
        const contact = await this.proposal.addRecipient({ name: this.input })
        this.isAdding = false
        this.isOpen = false
        this.$refs.triggerButton?.focus()
      } catch (e) {
        this.isAdding = false
      }
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
          this.focusedOption = null
        }
      }
    }
  }
}
</script>
