<template>
  <div class="bg-white shadow sm:rounded-lg">
    <div class="px-4 py-5 sm:p-6 relative">
      <LogoStripe class="h-15 text-indigo-600 absolute top-1" />

      <div class="sm:flex sm:items-start sm:justify-between">
        <div>
          <div class="mt-10 max-w-xl text-sm leading-5 text-gray-500">
            <p>
              Start accepting payments from your proposals with Stripe.
            </p>
          </div>
        </div>
      </div>

      <div class="mt-5">
        <!-- Gray container -->
        <div class="rounded-md bg-gray-50 px-6 py-5 sm:flex sm:items-start sm:justify-between shadow-inner">

          <!-- Connect new or existing stripe account -->
          <div v-if="!exists" class="flex flex-col sm:flex-row justify-between items-center w-full">
            <span class="inline-flex rounded-md shadow-sm mb-5 sm:mb-0 sm:mr-5">
              <inertia-link :href="oauthRoute" class="inline-flex items-center px-4 py-2 border border-gray-300 text-sm leading-5 font-medium rounded-md text-gray-700 bg-white hover:text-gray-500 focus:outline-none focus:border-blue-300 focus:shadow-outline-blue active:text-gray-800 active:bg-gray-50 transition ease-in-out duration-150 cursor-pointer">
                Connect Existing Account »
              </inertia-link>
            </span>

            <span class="inline-flex rounded-md shadow-sm">
              <inertia-link :href="onboardRoute" class="inline-flex items-center px-4 py-2 border border-gray-300 text-sm leading-5 font-medium rounded-md text-gray-700 bg-white hover:text-gray-500 focus:outline-none focus:border-blue-300 focus:shadow-outline-blue active:text-gray-800 active:bg-gray-50 transition ease-in-out duration-150 cursor-pointer">
                Setup New Stripe Account »
              </inertia-link>
            </span>
          </div>

          <!-- Connected stripe account details; with edit/disconnect -->
          <template v-if="exists">
            <div class="sm:flex sm:items-start">
              <div class="mt-3 sm:mt-0 sm:ml-4">
                <div class="text-sm leading-5 font-medium text-gray-900">
                  <EmojiFlag v-if="stripeAccount__.country" :code="stripeAccount__.country" class="align-middle mr-2"/>

                  <span v-if="stripeAccount__.dashboard_name">
                    {{ stripeAccount__.dashboard_name }} &middot;
                  </span>
                  {{ stripeAccount__.email }}
                </div>
                <div class="mt-1 text-sm leading-5 text-gray-600 sm:flex sm:items-center">
                  <div class="flex">
                    <svg v-if="stripeAccount__.is_stripe_integration_ok" class="h-5 w-5 text-green-400 inline-block mr-1" viewBox="0 0 20 20" fill="currentColor">
                      <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                    </svg>
                    <svg v-if="!stripeAccount__.is_stripe_integration_ok"  class="h-5 w-5 text-yellow-400 inline-block mr-1" viewBox="0 0 20 20" fill="currentColor">
                      <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
                    </svg>

                    {{ stripeAccount__.stripe_charges_payouts_state_text }}
                  </div>
                </div>
              </div>
            </div>
            <div class="mt-4 sm:mt-0 sm:ml-6 sm:flex-shrink-0">
              <span class="inline-flex rounded-md shadow-sm">
                <button type="button" class="inline-flex items-center px-4 py-2 border border-gray-300 text-sm leading-5 font-medium rounded-md text-gray-700 bg-white hover:text-gray-500 focus:outline-none focus:border-blue-300 focus:shadow-outline-blue active:text-gray-800 active:bg-gray-50 transition ease-in-out duration-150">
                  <!-- TODO stripe new onboarding account link editing... -->
                  Edit
                </button>
              </span>
              <span class="inline-flex rounded-md shadow-sm">
                <button @click="handleDisconnectClick" type="button" class="inline-flex items-center justify-center px-4 py-2 border border-transparent font-medium rounded-md text-red-700 bg-red-100 hover:bg-red-50 focus:outline-none focus:border-red-300 focus:shadow-outline-red active:bg-red-200 transition ease-in-out duration-150 sm:text-sm sm:leading-5">
                  &nbsp;
                  <svg class="h-5 w-5" fill="none" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" viewBox="0 0 24 24" stroke="currentColor"><path d="M6 18L18 6M6 6l12 12"></path></svg>
                  &nbsp;
                </button>
              </span>
            </div>
          </template>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import StripeAccount from '@/models/StripeAccount'
import EmojiFlag from '@/Components/EmojiFlag'
import LogoStripe from '@/Logos/LogoStripe'

export default {
  components: {
    EmojiFlag,
    LogoStripe
  },
  props: {
    orgUuid: { type: String },
    stripeAccount: { type: Object }
    // TODO extract.do better than an inertia link with strioe cibbect
  },
  data () {
    return {
      stripeAccount__: StripeAccount.make()
    }
  },
  watch: {
    stripeAccount: {
      immediate: true,
      handler (value) {
        this.stripeAccount__ = StripeAccount.make(value)
      }
    }
  },
  computed: {
    exists () {
      return this.stripeAccount__?.exists() ?? false
    },
    onboardRoute () {
      return route('use.org.stripe-connect-onboard-start', { org: this.orgUuid ?? '' })
    },
    oauthRoute () {
      return route('use.org.stripe-connect-oauth-start', { org: this.orgUuid ?? '' })
    },
  },
  methods: {
    handleDisconnectClick () {
      this.disconnect()
    },
    async disconnect () {
      try {
        await this.stripeAccount__.disconnect(this.orgUuid)
        this.$emit('disconnected')
      } catch (error) {

      }
    }
  }
}
</script>
