<template>
  <button
    type="button"
    :disabled="accepting"
    :class="{ 'cursor-wait': accepting }"
    class="inline-flex items-center p-6 border border-transparent rounded-full shadow-sm text-primary-yellow-500 focus:outline-none hover:shadow-md transition transform hover:scale-105 hover:-translate-y-1 duration-300 select-none"
    style="background-color: rgba(255, 255, 255, 0.5); backdrop-filter: blur(2px);"
    v-wave>

    <IconHeroiconsMediumCheck v-if="!accepting" class="h-24 w-24" />
    <IconHeroiconsSpinner v-else class="h-14 w-14 m-5"/>

  </button>
</template>

<script>
import IconHeroiconsMediumCheck from '@/Icons/IconHeroiconsMediumCheck'
import IconHeroiconsSpinner from '@/Icons/IconHeroiconsSpinner'

export default {
  name: 'PositAccept',
  components: {
    IconHeroiconsMediumCheck,
    IconHeroiconsSpinner
  },
  props: {
    accepting: {
      type: Boolean,
      default: false
    }
  },
  data () {
    return {

    }
  }
}
</script>
