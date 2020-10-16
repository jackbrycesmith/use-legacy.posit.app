<template>
  <div class="bg-white shadow overflow-hidden rounded-lg w-28 h-12 flex flex-col items-center justify-center relative border border-primary-yellow-400 cursor-pointer">

    <div class="absolute inline-flex items-center top-1 left-1">
      <input type="radio"
        class="form-radio h-4 w-4 text-primary-yellow-400 transition duration-150 ease-in-out"
        checked
        disabled>
    </div>

    <LogoStripe
      class="inline h-11 w-10 text-indigo-600 absolute inset-auto" />

  </div>
</template>

<script>
import LogoStripe from '@/Logos/LogoStripe'

export default {
  components: {
    LogoStripe
  },
  data () {
    return {

    }
  }
}
</script>
