<template>
  <SmoothReflow
    :options="{ property: 'height', transition: 'height .25s ease-in-out' }"
    tag="div"
    class="bg-white shadow sm:rounded-lg">
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
        <div class="rounded-md bg-gray-50 px-6 py-5 shadow-inner">

          <!-- Onboard New Stripe Account -->
          <div v-if="!exists" class="flex flex-col sm:flex-row justify-center items-center w-full">
            <span class="inline-flex rounded-md shadow-sm">
              <InertiaSingleButtonForm :route="accountLinkRoute">
                <template #button="{ form }">
                  <SmoothReflow
                    tag="button"
                    type="submit"
                    :disabled="form.processing"
                    :options="{ property: 'width', transition: 'width .2s ease-in' }"
                    :class="{ 'cursor-wait': form.processing }"
                    class="inline-flex items-center px-4 py-2 border border-gray-300 text-sm leading-5 font-medium rounded-md text-gray-700 bg-white hover:text-gray-500 focus:outline-none focus:border-blue-300 focus:shadow-outline-blue active:text-gray-800 active:bg-gray-50 transition ease-in-out duration-150 cursor-pointer relative pr-15 whitespace-no-wrap">
                      <IconHeroiconsSpinner v-if="form.processing" class="h-5 w-5 text-primary-yellow-400 absolute inset-y-auto left-4" />
                      <span
                      :class="{ 'ml-7': form.processing }"
                      class="whitespace-no-wrap">
                        Connect with
                      </span>
                      <LogoStripe
                        class="inline h-11 w-10 text-indigo-600 absolute inset-y-auto right-4" />
                  </SmoothReflow>
                </template>
              </InertiaSingleButtonForm>
            </span>
          </div>

          <!-- Connected stripe account details; with edit/disconnect -->
          <template v-if="exists">

            <!-- Title -->
            <h3 class="text-lg leading-6 font-medium text-gray-900 sm:ml-4 relative">
              Stripe Account

              <!-- Disconnect -->
              <InertiaSingleButtonForm
                :route="disconnectRoute"
                request-method="put"
                button-class="text-xs text-red-400 relative"
                class="absolute top-0 right-0 inset-y-auto">
                <template #button-inner="{ form }">
                  <IconHeroiconsSpinner v-if="form.processing" class="inline h-3 w-3 absolute inset-y-auto -ml-4 mt-1.5" />

                  <span class="underline align-text-bottom">Disconnect</span>
                </template>
              </InertiaSingleButtonForm>

            </h3>

            <div class="sm:flex sm:items-start">
              <div class="mt-1 sm:ml-4">

                <!-- Stripe Metadata Badges -->
                <div class="text-sm leading-5 font-medium text-gray-600 uppercase">
                  <span class="inline-flex">
                    <BadgeWithDotSmall color="blue" class="mr-1">
                      <template #dot>
                        <EmojiFlag v-if="stripeAccount__.country" :code="stripeAccount__.country" class="-ml-0.5 mr-1.5 w-full h-full align-middle"/>
                      </template>
                      {{ stripeAccount__.dashboard_name }}
                    </BadgeWithDotSmall>

                    <BadgeWithDotSmall color="blue">
                      {{ stripeAccount__.email }}
                    </BadgeWithDotSmall>
                  </span>
                </div>

                <!-- Stripe Connection Status Points -->
                <div class="mt-5 text-sm leading-5 text-gray-600 sm:flex sm:items-center">
                  <div class="flex">
                    <IconHeroiconsMediumCheckCircle
                      v-if="stripeAccount__.is_stripe_integration_ok"
                      class="h-5 w-5 text-green-400 inline-block mr-1" />

                    <svg v-if="!stripeAccount__.is_stripe_integration_ok"  class="h-5 w-5 text-yellow-400 inline-block mr-1" viewBox="0 0 20 20" fill="currentColor">
                      <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
                    </svg>

                    {{ stripeAccount__.stripe_charges_payouts_state_text }}
                  </div>
                </div>
              </div>
            </div>

            <!-- Stripe Account Link Button -->
            <div class="mt-5 sm:ml-4 sm:flex-shrink-0">
              <span class="inline-flex rounded-md shadow-sm">
                <InertiaSingleButtonForm :route="accountLinkRoute">
                  <template #button="{ form }">
                    <SmoothReflow
                      tag="button"
                      type="submit"
                      :disabled="form.processing"
                      :options="{ property: 'width', transition: 'width .2s ease-in' }"
                      :class="{ 'cursor-wait': form.processing }"
                      class="inline-flex items-center px-4 py-2 border border-gray-300 text-sm leading-5 font-medium rounded-md text-gray-700 bg-white hover:text-gray-500 focus:outline-none focus:border-blue-300 focus:shadow-outline-blue active:text-gray-800 active:bg-gray-50 transition ease-in-out duration-150 cursor-pointer relative whitespace-no-wrap">
                        <IconHeroiconsSpinner v-if="form.processing" class="h-5 w-5 text-primary-yellow-400 absolute inset-y-auto left-4" />
                        <span
                        :class="{ 'ml-7': form.processing }"
                        class="whitespace-no-wrap">
                          {{ stripeAccount__.account_link_text }}
                        </span>
                    </SmoothReflow>
                  </template>
                </InertiaSingleButtonForm>
              </span>
            </div>
          </template>
        </div>
      </div>
    </div>
  </SmoothReflow>
</template>

<script>
import StripeAccount from '@/models/StripeAccount'
import EmojiFlag from '@/Components/EmojiFlag'
import LogoStripe from '@/Logos/LogoStripe'
import BaseForm from '@/Forms/BaseForm'
import InertiaSingleButtonForm from '@/Components/InertiaSingleButtonForm'
import BadgeWithDotSmall from '@/Components/TailwindUI/BadgeWithDotSmall'
import SmoothReflow from '@/Components/SmoothReflow'
import IconHeroiconsSpinner from '@/Icons/IconHeroiconsSpinner'
import IconHeroiconsMediumCheckCircle from '@/Icons/IconHeroiconsMediumCheckCircle'

export default {
  components: {
    BadgeWithDotSmall,
    BaseForm,
    EmojiFlag,
    LogoStripe,
    InertiaSingleButtonForm,
    IconHeroiconsSpinner,
    IconHeroiconsMediumCheckCircle,
    SmoothReflow
  },
  props: {
    teamUuid: { type: String },
    stripeAccount: { type: Object }
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
    accountLinkRoute () {
      return this.$route('use.team.stripe-connect-account-link', { team: this.teamUuid ?? '' })
    },
    disconnectRoute () {
      return this.$route('use.submit.disconnect-stripe-account', { team: this.teamUuid ?? '' })
    },
  },
}
</script>
