<template>
  <div class="min-h-screen flex items-center justify-center bg-primary-yellow-50 py-12 px-4 sm:px-6 lg:px-8">
    <div class="max-w-md w-full">
      <div>
        <img class="mx-auto h-12 w-auto" src="/posit-icon-2-grey-gold.svg" alt="Posit.app logo">
        <h2 class="mt-6 text-center text-3xl leading-9 font-extrabold text-gray-900">
          Enter Access Code
        </h2>
        <p class="mt-2 text-center text-sm leading-5 text-gray-600">
          <!--          -->
        </p>
      </div>

      <PositAuthForm
        :posit-uuid="posit__.uuid"
        class="mt-8"/>

    </div>
  </div>
</template>

<script>
import Posit from '@/models/Posit'
import PositAuthForm from '@/Forms/PositAuthForm'

export default {
  components: { PositAuthForm },
  props: {
    posit: { type: Object }
  },
  data () {
    return {
      posit__: Posit.make()
    }
  },
  watch: {
    posit: {
      immediate: true,
      handler (value) {
        this.posit__ = Posit.make(value)
      }
    }
  }
}
</script>
