<template>
  <!-- On: "bg-indigo-600", Off: "bg-gray-200" -->
  <button
    type="button"
    @click.prevent="toggle"
    :aria-pressed="modelValue"
    :class="`${buttonColorClass} ${disabled ? 'cursor-not-allowed' : ''}`"
    class="relative inline-flex flex-shrink-0 h-6 w-11 border-2 border-transparent rounded-full cursor-pointer transition-colors ease-in-out duration-200 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
    <span class="sr-only">{{ srOnlyText }}</span>

    <span
      :class="{ 'translate-x-5': modelValue, 'translate-x-0': !modelValue }"
      class="relative inline-block h-5 w-5 rounded-full bg-white shadow transform ring-0 transition ease-in-out duration-200">

      <span
        :class="{ 'opacity-0 ease-out duration-100': modelValue, 'opacity-100 ease-in duration-200': !modelValue }"
        class="absolute inset-0 h-full w-full flex items-center justify-center transition-opacity" aria-hidden="true">
        <svg class="h-3 w-3 text-gray-400" fill="none" viewBox="0 0 12 12">
          <path d="M4 8l2-2m0 0l2-2M6 6L4 4m2 2l2 2" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
        </svg>
      </span>


      <span
        :class="{ 'opacity-100 ease-in duration-200': modelValue, 'opacity-0 ease-out duration-100': !modelValue }"
        class="absolute inset-0 h-full w-full flex items-center justify-center transition-opacity" aria-hidden="true">
        <svg
          :class="iconColorClass"
          class="h-3 w-3 text-indigo-600" fill="currentColor" viewBox="0 0 12 12">
          <path d="M3.707 5.293a1 1 0 00-1.414 1.414l1.414-1.414zM5 8l-.707.707a1 1 0 001.414 0L5 8zm4.707-3.293a1 1 0 00-1.414-1.414l1.414 1.414zm-7.414 2l2 2 1.414-1.414-2-2-1.414 1.414zm3.414 2l4-4-1.414-1.414-4 4 1.414 1.414z" />
        </svg>
      </span>
    </span>
  </button>
</template>

<script>
import { has, get, debounce } from 'lodash-es'

const COLORS = {
  'indigo': {
    buttonBgOff: 'bg-gray-200',
    buttonBgOn: 'bg-indigo-600',
    buttonCommon: 'focus:ring-indigo-500',
    iconColor: 'text-indigo-600'
  },
  'primary-yellow': {
    buttonBgOff: 'bg-gray-200',
    buttonBgOn: 'bg-primary-yellow-600',
    buttonCommon: 'focus:ring-primary-yellow-500',
    iconColor: 'text-primary-yellow-600'
  }
}

export default {
  name: 'ToggleButton',
  props: {
    srOnlyText: {
      type: String,
      default: 'Use setting'
    },
    modelValue: {
      type: Boolean,
      default: null
    },
    disabled: {
      type: Boolean,
      default: false
    },
    color: {
      type: String,
      default: 'indigo',
      validator (val) { return has(COLORS, val) }
    },
    notifyChangeDebounceMs: {
      type: Number,
      default: 1000
    },
  },
  computed: {
    colorObject () {
      return get(COLORS, this.color)
    },
    buttonColorClass () {
      return this.modelValue
        ? `${this.colorObject.buttonBgOn} ${this.colorObject.buttonCommon}`
        : `${this.colorObject.buttonBgOff} ${this.colorObject.buttonCommon}`
    },
    iconColorClass () {
      return this.colorObject.iconColor
    }
  },
  created () {
    this.notifyChange = debounce((vm) => {
      vm.$emit('changed')
    }, this.notifyChangeDebounceMs)

    this.$once('hook:destroyed', () => {
      this.notifyChange = null
    })
  },
  methods: {
    toggle() {
      if (this.disabled) {
        return
      }

      this.$emit('update:modelValue', !this.modelValue)
      this.notifyChange(this)
    }
  },
}
</script>
