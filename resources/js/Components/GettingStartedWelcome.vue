<template>
  <div class="bg-white overflow-hidden shadow rounded-lg">
    <div class="bg-primary-yellow-200 rounded-t-lg pt-5 pb-2 px-4 sm:px-6 lg:px-8">
      <h3 class="text-lg leading-6 font-medium text-gray-900">
        <ApplicationMark class="inline-block h-7 w-7 -ml-1 mr-0.5" /> Getting Started
      </h3>

      <div class="mt-2 max-w-xl text-sm leading-5 text-gray-500 mb-4">
        <p>
          Any issues? » <a class="underline text-black" href="mailto:help@posit.app">help@posit.app</a>
        </p>
      </div>
    </div>

    <div class="py-5 px-4 sm:px-6 lg:px-8">
      <nav class="flex">
        <ul class="space-y-6">
          <li>
            <!-- Complete Step -->
            <!-- <a href="#" class="group"> -->
              <div class="flex items-start space-x-3">
                <div class="flex-shrink-0 relative h-5 w-5 flex items-center justify-center">
                  <IconHeroiconsSmallCheckCircle
                    class="h-full w-full text-primary-yellow-600 group-hover:text-primary-yellow-800 group-focus:text-primary-yellow-800 transition ease-in-out duration-150"
                  />

                </div>
                <p class="text-sm leading-5 font-medium text-gray-500 group-hover:text-gray-900 group-focus:text-gray-900 transition ease-in-out duration-150">Create account</p>
              </div>
            <!-- </a> -->
          </li>

          <li>
            <!-- Current Step -->
            <component :is="paymentProviderSetupComponent" v-bind="paymentProviderSetupAttrs">
              <div class="flex items-start space-x-3">
                <div class="flex-shrink-0 h-5 w-5 relative flex items-center justify-center">
                  <IconHeroiconsSmallCheckCircle
                    v-if="hasSetupPaymentProvider"
                    class="h-full w-full text-primary-yellow-600 group-hover:text-primary-yellow-800 group-focus:text-primary-yellow-800 transition ease-in-out duration-150"
                  />

                  <template v-else>
                    <!-- Current one... -->
                    <span :class="{ 'animate-ping': animateCurrentActiveStep }" class="absolute h-4 w-4 rounded-full bg-primary-yellow-200"></span>
                    <span class="relative block w-2 h-2 bg-primary-yellow-600 rounded-full"></span>
                  </template>
                </div>
                <p
                  :class="hasSetupPaymentProvider ? 'text-gray-500' : 'text-primary-yellow-600'"
                  class="text-sm leading-5 font-medium">
                  Setup payments provider
                </p>
              </div>
            </component>
          </li>

          <li>
            <!-- Upcoming Step -->
            <!-- <a href="#" class="group"> -->
              <div class="flex items-start space-x-3">
                <div class="flex-shrink-0 h-5 w-5 relative flex items-center justify-center">

                  <template v-if="!hasSetupPaymentProvider && !hasSentFirstPosit">
                    <span class="block h-2 w-2 bg-gray-300 rounded-full group-hover:bg-gray-400 group-focus:bg-gray-400 transition ease-in-out duration-150"></span>
                  </template>

                  <template v-if="hasSetupPaymentProvider && !hasSentFirstPosit">
                    <!-- Current one... -->
                    <span
                      :class="{ 'animate-ping': animateCurrentActiveStep }"
                      class="absolute h-4 w-4 rounded-full bg-primary-yellow-200"/>
                    <span class="relative block w-2 h-2 bg-primary-yellow-600 rounded-full"></span>
                  </template>

                  <template v-if="hasSentFirstPosit">
                    <IconHeroiconsSmallCheckCircle
                      class="h-full w-full text-primary-yellow-600 group-hover:text-primary-yellow-800 group-focus:text-primary-yellow-800 transition ease-in-out duration-150"
                    />
                  </template>

                </div>
                <p
                  :class="(!hasSetupPaymentProvider || hasSentFirstPosit ) ? 'text-gray-500' : 'text-primary-yellow-600'"
                  class="text-sm leading-5 font-medium text-gray-500 group-hover:text-gray-900 group-focus:text-gray-900 transition ease-in-out duration-150">
                  Send your 1<sup>st</sup> posit
                </p>
              </div>
            <!-- </a> -->
          </li>

        </ul>
      </nav>
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
    paymentProviderSetupAttrs () {
      return {
        href: this.team.route_settings
      }
    },
    hasSetupPaymentProvider () {
      return this.team?.has_setup_payment_provider ?? false
    },
    hasSentFirstPosit () {
      return this.team?.has_sent_first_proposal ?? false
    }
  },
  data () {
    return {

    }
  }
}
</script>
