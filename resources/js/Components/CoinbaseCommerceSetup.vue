<template>
  <div class="bg-white shadow sm:rounded-lg">
    <div class="px-4 py-5 sm:p-6 relative">
      <LogoCoinbase class="text-blue-700 h-24 w-24 -mt-5 absolute top-1" />

      <div class="sm:flex sm:items-start sm:justify-between">
        <div>
          <div class="mt-10 max-w-xl text-sm leading-5 text-gray-500">
            <p>
              Start accepting crypto payments from your posits with Coinbase Commerce.
            </p>
          </div>
        </div>
      </div>

      <div class="mt-5">
        <!-- Gray container -->
        <div class="rounded-md bg-gray-50 px-6 py-5 sm:flex sm:items-start sm:justify-between shadow-inner">
          <p class="w-full text-gray-600 uppercase text-center font-medium tracking-wider">
            <span class="mr-2">🚧</span>Coming Soon
          </p>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import StripeAccount from '@/models/StripeAccount'
import LogoCoinbase from '@/Logos/LogoCoinbase'

export default {
  components: {
    LogoCoinbase
  },
  props: {
  },
  data () {
    return {

    }
  },
}
</script>
