<template>
  <div>
    <label for="deposit" class="block text-sm font-medium leading-5 text-gray-600 mb-2">
      Payment
    </label>

    <nav>
      <ul class="">

        <li class="relative pb-10">
          <!-- Current Step -->
          <!-- <div class="-ml-px absolute mt-0.5 top-4 left-4 w-0.5 h-full bg-gray-300" /> -->

          <div class="relative flex items-start space-x-4 group focus:outline-none">
            <div class="h-9 flex items-center">
              <!-- z-10 removed... -->
              <span class="relative w-8 h-8 flex items-center justify-center bg-white border-2 border-primary-yellow-500 rounded-full">
                <span class="h-2.5 w-2.5 flex items-center justify-center text-primary-yellow-500">
                  1
                </span>
              </span>
            </div>
            <div class="min-w-0">
              <h3 class="text-xs leading-4 font-semibold uppercase tracking-wide text-primary-yellow-500">
                Acceptance
              </h3>
              <p class="text-xs sm:text-sm leading-5 text-gray-500">
                Upfront payment to start the project.
              </p>
            </div>
          </div>

          <div class="ml-12 mt-2">

            <div>
              <label class="block text-sm font-medium leading-5 text-gray-600">
                Provider
              </label>

              <div class="flex gap-x-3 mt-1">
                <PositPaymentProviderStripe />
                <PositPaymentProviderCoinbase />
              </div>

              <!-- Provider Integration Not Ready Message -->
              <template v-if="!isSelectedProviderReady">
                <inertia-link
                  :href="settingsRoute"
                  class="block text-xs leading-5 text-red-500 mt-2 hover:text-red-400">
                  <IconHeroiconsMediumExclamation
                    class="h-5 w-5 mr-1 inline-block" />

                    <span class="align-middle">Provider not ready, see settings »</span>
                </inertia-link>
              </template>

              <!-- Amount -->
              <InputWithCurrency
                :currency-model="posit.value_currency_code"
                :amount-model.sync="posit.deposit_amount"
                :max="999999999"
                :editable="editable"
                :can-switch-currency="false"
                label="Amount"
                @changed="handleUpdateDepositAmount"
                class="mt-4 space-y-1" />

            </div>
          </div>
        </li>
      </ul>
    </nav>

  </div>
</template>

<script>
import PositPaymentProviderStripe from '@/Components/PositPaymentProviderStripe'
import PositPaymentProviderCoinbase from '@/Components/PositPaymentProviderCoinbase'
import IconHeroiconsMediumExclamation from '@/Icons/IconHeroiconsMediumExclamation'
import InputWithCurrency from '@/Components/TailwindUI/InputWithCurrency'

export default {
  components: {
    PositPaymentProviderStripe,
    PositPaymentProviderCoinbase,
    IconHeroiconsMediumExclamation,
    InputWithCurrency
  },
  props: {
    posit: { type: Object },
    editable: {
      type: Boolean,
      default: true
    },
  },
  data () {
    return {

    }
  },
  computed: {
    settingsRoute () {
      return this.posit.org.route_settings
    },
    isSelectedProviderReady () {
      return this.posit.is_selected_payment_provider_ready ?? false
    }
  },
  methods: {
    async handleUpdateDepositAmount () {
      if (!this.editable) return

      try {
        await this.posit.updateDepositAmount()
      } catch (e) {
        // TODO handle update project value error...
      }
    }
  }
}
</script>
