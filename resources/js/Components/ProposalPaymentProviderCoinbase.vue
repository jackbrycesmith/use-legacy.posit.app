<template>
  <div class="bg-white shadow overflow-hidden rounded-lg w-28 h-12 flex flex-col items-center justify-center relative border cursor-not-allowed">

    <div class="absolute inline-flex items-center top-1 left-1">
      <span class="text-xs">🚧</span>
    </div>

    <span class="absolute inset-auto">
      <LogoCoinbase
        class="inline h-15 w-32 text-blue-700 opacity-50" />
    </span>

  </div>
</template>

<script>
import LogoCoinbase from '@/Logos/LogoCoinbase'

export default {
  components: {
    LogoCoinbase
  },
  data () {
    return {

    }
  }
}
</script>
