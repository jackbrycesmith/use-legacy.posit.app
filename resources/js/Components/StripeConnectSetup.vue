<template>
  <div class="bg-white shadow sm:rounded-lg">
    <div class="px-4 py-5 sm:p-6 relative">
        <!-- Stripe Logo -->
        <svg class="h-15 text-indigo-600 absolute top-1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" aria-hidden="true" focusable="false" style="vertical-align: -0.125em;-ms-transform: rotate(360deg); -webkit-transform: rotate(360deg); transform: rotate(360deg);" preserveAspectRatio="xMidYMid meet" viewBox="0 0 32 32"><path d="M8.25 10.435l-2.165.46l-.01 7.12c0 1.315.99 2.165 2.305 2.165c.73 0 1.265-.135 1.56-.295v-1.69c-.285.115-1.685.525-1.685-.785v-3.16H9.94v-1.89H8.255zm4.455 2.58l-.135-.655h-1.92v7.66h2.215v-5.155c.525-.69 1.41-.555 1.695-.465v-2.04c-.3-.105-1.335-.3-1.855.655zM17.32 9.4l-2.23.475v1.81l2.23-.475zM2.245 14.615c0-.345.29-.48.755-.485c.675 0 1.535.205 2.21.57v-2.09a5.925 5.925 0 0 0-2.205-.405c-1.8 0-3 .94-3 2.51c0 2.46 3.375 2.06 3.375 3.12c0 .41-.355.545-.85.545c-.735 0-1.685-.305-2.43-.71v2c.825.355 1.66.505 2.425.505c1.845 0 3.115-.79 3.115-2.39c0-2.645-3.395-2.17-3.395-3.17zM32 16.28c0-2.275-1.1-4.07-3.21-4.07s-3.395 1.795-3.395 4.055c0 2.675 1.515 3.91 3.675 3.91c1.06 0 1.855-.24 2.46-.575v-1.67c-.605.305-1.3.49-2.18.49c-.865 0-1.625-.305-1.725-1.345h4.345c.01-.115.03-.58.03-.795zm-4.395-.84c0-1 .615-1.42 1.17-1.42c.545 0 1.125.42 1.125 1.42zm-5.645-3.23c-.87 0-1.43.41-1.74.695l-.115-.55H18.15v10.24l2.22-.47l.005-2.51c.32.235.795.56 1.57.56c1.59 0 3.04-1.16 3.04-3.98c.005-2.58-1.465-3.985-3.025-3.985zm-.53 6.125c-.52 0-.83-.19-1.045-.42l-.015-3.3c.23-.255.55-.44 1.06-.44c.81 0 1.37.91 1.37 2.07c.005 1.195-.545 2.09-1.37 2.09zm-6.335 1.685h2.23v-7.66h-2.23z" fill="currentColor"/></svg>

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
        <div class="rounded-md bg-gray-50 px-6 py-5 sm:flex sm:items-start sm:justify-between">

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

export default {
  components: { EmojiFlag },
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
