<template>
  <span
    :class="badgeClass"
    class="inline-flex items-center rounded-full font-medium">
    <slot v-if="showDot" name="dot">
      <svg :class="dotClass" fill="currentColor" viewBox="0 0 8 8">
        <circle cx="4" cy="4" r="3" />
      </svg>
    </slot>
    <slot />
  </span>
</template>

<script>
import { has, get } from 'lodash-es'

const COLORS = {
  'indigo': {
    badgeColor: 'bg-indigo-100 text-indigo-800',
    dotColor: 'text-indigo-400'
  },
  'pink': {
    badgeColor: 'bg-pink-100 text-pink-800',
    dotColor: 'text-pink-400'
  },
  'blue': {
    badgeColor: 'bg-blue-100 text-blue-800',
    dotColor: 'text-blue-400'
  },
  'gray': {
    badgeColor: 'bg-gray-100 text-gray-800',
    dotColor: 'text-gray-400'
  },
  'yellow': {
    badgeColor: 'bg-yellow-100 text-yellow-800',
    dotColor: 'text-yellow-400'
  },
  'orange': {
    badgeColor: 'bg-orange-100 text-orange-800',
    dotColor: 'text-orange-400'
  },
  'green': {
    badgeColor: 'bg-green-100 text-green-800',
    dotColor: 'text-green-400'
  },
}

export default {
  props: {
    size: {
      default: 'small',
      validator (val) {
        return ['small', 'large'].includes(val)
      }
    },
    color: {
      type: String,
      default: 'blue',
      validator (val) { return has(COLORS, val) }
    },
    customBadgeClass: {
      type: String
    },
    customDotClass: {
      type: String
    },
    showDot: {
      type: Boolean,
      default: true
    }
  },
  computed: {
    badgeSizeClass () {
      if (this.size === 'small') {
        return 'px-2.5 py-0.5 text-xs leading-4'
      }

      if (this.size === 'large') {
        return 'px-3 py-0.5 text-sm leading-5'
      }
    },
    dotSizeClass () {
      if (this.size === 'small') {
        return '-ml-0.5 mr-1.5 h-2 w-2'
      }

      if (this.size === 'large') {
        return '-ml-1 mr-1.5 h-2 w-2'
      }
    },
    badgeClass () {
      return `${this.badgeSizeClass} ${this.badgeColorClass}`
    },
    dotClass () {
      return `${this.dotSizeClass} ${this.dotColorClass}`
    },
    badgeColorClass () {
      if (this.customBadgeClass) {
        return this.customBadgeClass
      }

      return get(COLORS, `${this.color}.badgeColor`)
    },
    dotColorClass () {
      if (this.customDotClass) {
        return this.customDotClass
      }

      return get(COLORS, `${this.color}.dotColor`)
    }
  }
}
</script>
