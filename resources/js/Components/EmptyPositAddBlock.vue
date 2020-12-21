<template>
  <button
    type="button"
    class="inline-flex items-center p-3 border border-transparent rounded-full shadow-sm text-gray-400 hover:text-gray-500 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-yellow-500"
    style="background-color: rgba(255, 255, 255, 0.5); backdrop-filter: blur(2px);"
    title="Add block"
    @click.prevent="$emit('add')">
    <IconHeroiconsMediumPlus class="h-6 w-6 select-none" />
  </button>
</template>

<script>
import IconHeroiconsMediumPlus from '@/Icons/IconHeroiconsMediumPlus'

export default {
  name: 'EmptyPositAddBlock',
  components: {
    IconHeroiconsMediumPlus
  },
  data () {
    return {

    }
  }
}
</script>
