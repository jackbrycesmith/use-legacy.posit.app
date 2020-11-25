<template>
  <div class="bg-white overflow-hidden shadow rounded-lg">
    <div class="px-4 py-5 sm:p-6">
      <h2 class="text-2xl font-semibold text-gray-800 text-center">
        <ApplicationLogo class="h-10 w-40 mx-auto" />
      </h2>

      <Tabs
        :selected-index.sync="tabIndex"
        :show-tab-text-when-inactive="true"
        active-tab-class="border-b-2 border-primary-yellow-500 text-primary-yellow-600 focus:outline-none focus:text-primary-yellow-800 focus:border-primary-yellow-700"
        active-tab-icon-class="text-primary-yellow-500 group-focus:text-primary-yellow-600"
        tabs-class="mt-4">

        <TabPane
          v-for="paddleProduct in paddleProducts"
          :key="paddleProduct.product_id"
          :title="`${paddleProduct.credits} Credits`">
          <div class="">

          </div>
        </TabPane>
      </Tabs>

      <div class="flex flex-col mt-2 h-20 relative">

        <span v-if="paddleProductPrices" class="text-xs leading-6 font-medium text-center text-gray-500 uppercase">
          Price
        </span>
        <div class="flex items-center justify-center text-5xl leading-none font-extrabold text-gray-900">
          <span>
            {{ selectPaddlePriceFormatted }}
          </span>
          <span class="ml-3 text-xl leading-7 font-medium text-gray-500">
            {{ selectedPaddleProductCurrencyCode }}
          </span>
        </div>

        <div
          v-if="fetchingPaddleProductPrices || errorFetchingPaddleProductPrices"
          class="absolute h-full w-full flex flex-col items-center justify-center">
          <IconHeroiconsSpinner
            v-if="fetchingPaddleProductPrices"
            class="inline-block h-7 w-7 text-gray-600" />

          <span
            v-if="errorFetchingPaddleProductPrices"
            class="text-xs leading-6 font-medium text-center text-red-500 uppercase">
            Error fetching price
          </span>

          <button
            v-if="errorFetchingPaddleProductPrices"
            type="button"
            @click.prevent="fetchPaddleProductPrices"
            class="inline-flex items-center px-3 py-2 border border-gray-300 shadow-sm text-xs font-medium rounded-full text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-yellow-500">
            <span class="sr-only">Retry</span>
            <IconHeroiconsSmallRefresh class="h-4 w-4" />
          </button>

        </div>
      </div>

      <!-- What the credits purchase will allow you to do -->
      <div class="mt-6 w-full inline-flex justify-center">
        <ul class="space-y-4">
          <li class="flex space-x-3">
            <IconHeroiconsMediumCheck class="flex-shrink-0 h-5 w-5 text-green-500" />
            <!-- TODO custom amount -->
            <span class="text-sm text-gray-500">Publish {{ selectedPaddleProduct.credits }} proposals</span>
          </li>

          <li class="flex space-x-3">
            <IconHeroiconsMediumCheck class="flex-shrink-0 h-5 w-5 text-green-500" />
            <span class="text-sm text-gray-500">No expiry date</span>
          </li>

          <li class="flex space-x-3">
            <IconHeroiconsMediumCheck class="flex-shrink-0 h-5 w-5 text-green-500" />
            <span class="text-sm text-gray-500">Zero payment processing fees</span>
          </li>
        </ul>
      </div>

      <div class="mt-8 flex justify-center items-center">
        <AjaxSingleButtonForm
          :route="paddlePayLinkRoute"
          :request-data="payLinkRequestData"
          request-method="put"
          @success="handleFetchPayLinkSuccess"
          @error="handleFetchPayLinkError"
        >
          <template #button="{ isFormProcessing }">
            <button
              type="submit"
              :disabled="isFormProcessing"
              :class="{ 'cursor-wait': isFormProcessing }"
              class="inline-flex items-center justify-center px-6 py-3 border border-transparent text-base font-medium rounded-md bg-primary-yellow-400 hover:bg-primary-yellow-300 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-yellow-500 relative"
              style="min-width: 200px;">

              Get {{ selectedPaddleProduct.credits }} Credits

            </button>
          </template>

        </AjaxSingleButtonForm>
      </div>

    </div>
  </div>
</template>

<script>
import Tabs from '@/Components/Tabs'
import TabPane from '@/Components/TabPane'
import IconCredits from '@/Icons/IconCredits'
import IconHeroiconsSpinner from '@/Icons/IconHeroiconsSpinner'
import IconHeroiconsMediumCheck from '@/Icons/IconHeroiconsMediumCheck'
import IconHeroiconsSmallRefresh from '@/Icons/IconHeroiconsSmallRefresh'
import ApplicationLogo from '@/Jetstream/ApplicationLogo'
import EmojiFlag from '@/Components/EmojiFlag'
import AjaxSingleButtonForm from '@/Components/AjaxSingleButtonForm'
import { join, map, nth, isEmpty, get, find } from 'lodash-es'
import { jsonp } from 'vue-jsonp'
import { formatCurrency } from '@/utils/strings'
import { sleepToEnsureMinTimeSpent } from '@/utils/sleep'

export default {
  name: 'GetMoreCredits',
  components: {
    Tabs,
    TabPane,
    IconCredits,
    IconHeroiconsSpinner,
    IconHeroiconsMediumCheck,
    IconHeroiconsSmallRefresh,
    ApplicationLogo,
    AjaxSingleButtonForm,
    EmojiFlag
  },
  props: {
    paddleProducts: {
      type: Array
    },
    paddlePayLinkRoute: {
      type: String
    },
    minTimeSpentFetchingPaddlePricesMs: {
      type: Number,
      default: 750
    },
    paddleCheckoutPricesApiUrl: {
      type: String,
      default: 'https://checkout.paddle.com/api/2.0/prices'
    }
  },
  computed: {
    selectedPaddleProduct () {
      return nth(this.paddleProducts, this.tabIndex) ?? {}
    },
    payLinkRequestData () {
      return {
        product_id: this.selectedPaddleProduct?.product_id
      }
    },
    paddleProductIds () {
      return map(this.paddleProducts, 'product_id')
    },
    selectedPaddleProductPriceData () {
      if (isEmpty(this.selectedPaddleProduct) || isEmpty(this.paddleProductPrices)) {
        return null
      }

      return find(this.paddleProductPrices['products'], { product_id: this.selectedPaddleProduct?.product_id })
    },
    paddleCustomerCountryCode () {
      return get(this.paddleProductPrices, 'customer_country')
    },
    selectedPaddleProductCurrencyCode () {
      return this.selectedPaddleProductPriceData?.currency
    },
    selectPaddlePriceFormatted () {
      if (isEmpty(this.selectedPaddleProductPriceData)) {
        return null
      }

      const amount = get(this.selectedPaddleProductPriceData, 'price.net')

      return formatCurrency(amount, this.selectedPaddleProductCurrencyCode)
    }
  },
  mounted () {
    this.fetchPaddleProductPrices()
  },
  data () {
    return {
      tabIndex: 0,
      paddleProductPrices: null,
      fetchingPaddleProductPrices: false,
      errorFetchingPaddleProductPrices: false
    }
  },
  methods: {
    handleFetchPayLinkSuccess (response) {
      window.Paddle.Checkout.open({
        override: response.pay_link,
        successCallback: this.handlePaddleSuccessfulCheckout
      })
    },
    handlePaddleSuccessfulCheckout () {
      console.log('handlePaddleSuccessfulCheckout')
    },
    handleFetchPayLinkError (error) {
      console.log('handleFetchPayLinkError: ', error)
    },
    async fetchPaddleProductPrices () {
      if (this.fetchingPaddleProductPrices) {
        return
      }

      this.fetchingPaddleProductPrices = true
      this.errorFetchingPaddleProductPrices = false

      await this.$nextTick()

      const timeBefore = performance.now()
      try {
        const response = await jsonp(this.paddleCheckoutPricesApiUrl, {
          product_ids: join(this.paddleProductIds, ', ')
        })

        await sleepToEnsureMinTimeSpent(timeBefore, this.minTimeSpentFetchingPaddlePricesMs)
        this.paddleProductPrices = response.response
      } catch (e) {
        await sleepToEnsureMinTimeSpent(timeBefore, this.minTimeSpentFetchingPaddlePricesMs)
        this.errorFetchingPaddleProductPrices = true
      } finally {
        this.fetchingPaddleProductPrices = false
      }
    }
  }
}
</script>
