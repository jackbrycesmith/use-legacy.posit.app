<template>
  <div class="bg-white overflow-hidden shadow rounded-lg">
    <div class="bg-primary-yellow-200 rounded-t-lg pt-5 pb-2 px-4 sm:px-6 lg:px-8">
      <h3 class="text-lg leading-6 font-medium text-gray-900">
        <ApplicationMark class="inline-block h-7 w-7 -ml-1 mr-0.5" /> Credit History
      </h3>

      <div class="mt-2 max-w-xl text-sm leading-5 text-gray-500 mb-4">
        <p>
          Problems? » <a class="underline text-black" href="mailto:help@posit.app">help@posit.app</a>
        </p>
      </div>
    </div>

    <div class="py-5 px-4 sm:px-6 lg:px-8">
      <div class="flex flex-col">


        <button
          type="button"
          class="inline-flex items-center px-6 py-3 border border-transparent rounded-md text-primary-yellow-600 justify-center text-sm font-semibold hover:bg-primary-yellow-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-yellow-500 text-center mt-4">
          View all history <span aria-hidden="true" class="ml-0.5">&rarr;</span>
        </button>
      </div>
    </div>
  </div>
</template>

<script>
import ApplicationMark from '@/Jetstream/ApplicationMark'
import IconHeroiconsSmallCheckCircle from '@/Icons/IconHeroiconsSmallCheckCircle'

export default {
  name: 'GettingStartedWelcome',
  components: {
    ApplicationMark,
    IconHeroiconsSmallCheckCircle
  },
  props: {
    team: {
      type: Object
    },
    animateCurrentActiveStep: {
      type: Boolean,
      default: true
    }
  },
  computed: {
    paymentProviderSetupComponent () {
      return 'inertia-link'
    },
    // paymentProviderSetupAttrs () {
    //   return {
    //     href: this.team.route_settings
    //   }
    // },
    // hasSetupPaymentProvider () {
    //   return this.team?.has_setup_payment_provider ?? false
    // },
    // hasSentFirstProposal () {
    //   return this.team?.has_sent_first_proposal ?? false
    // }
  },
  data () {
    return {

    }
  }
}
</script>
