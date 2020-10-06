<template>
  <div class="bg-white shadow overflow-hidden rounded-lg w-32 h-16 flex flex-col items-center justify-center relative border border-primary-yellow-400 cursor-pointer">

    <!-- Is Active? -->
    <!-- <IconHeroiconsMediumCheckCircle class="absolute w-4 h-4 text-green-400 top-1 left-1" /> -->
    <div class="absolute inline-flex items-center top-1 left-1">
      <input type="radio"
        class="form-radio h-4 w-4 text-primary-yellow-400 transition duration-150 ease-in-out"
        checked
        disabled>
    </div>

    <!-- Name -->
    <span class="font-medium text-gray-600 text-xs">
      {{ name }}
    </span>

    <!-- Color swatches -->
    <div class="flex overflow-hidden">

      <div class="inline-block h-6 w-6 rounded-full text-white shadow-solid | bg-cool-gray-300"/>
      <div class="-ml-1 inline-block h-6 w-6 rounded-full text-white shadow-solid | bg-gray-50 "/>
    </div>

  </div>
</template>

<script>
import IconHeroiconsMediumCheckCircle from '@/Icons/IconHeroiconsMediumCheckCircle'

export default {
  components: {
    IconHeroiconsMediumCheckCircle
  },
  props: {
    name: { type: String },
    colors: { type: Array },
    active: { type: Boolean, default: true },
  },
  data () {
    return {

    }
  }
}
</script>
