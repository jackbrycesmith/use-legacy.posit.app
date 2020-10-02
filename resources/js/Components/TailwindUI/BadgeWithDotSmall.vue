<template>
  <span
    :class="badgeColorClass"
    class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium leading-4">
    <slot name="dot">
      <svg :class="dotColorClass" class="-ml-0.5 mr-1.5 h-2 w-2" fill="currentColor" viewBox="0 0 8 8">
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
    color: {
      type: String,
      default: 'blue',
      validator (val) { return has(COLORS, val) }
    }
  },
  computed: {
    badgeColorClass () {
      return get(COLORS, `${this.color}.badgeColor`)
    },
    dotColorClass () {
      return get(COLORS, `${this.color}.dotColor`)
    }
  }
}
</script>
