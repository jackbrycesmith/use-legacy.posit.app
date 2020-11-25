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

      <!-- TODO Localised Price amount -->
      <div class="flex flex-col mt-2">
        <span class="text-xs leading-6 font-medium text-center text-gray-500 uppercase">
          Price includes tax <EmojiFlag
              v-if="paddleCustomerCountryCode"
              :code="paddleCustomerCountryCode"/>
        </span>
        <div class="flex items-center justify-center text-5xl leading-none font-extrabold text-gray-900">
          <span>
            {{ selectPaddlePriceFormatted }}
          </span>
          <span class="ml-3 text-xl leading-7 font-medium text-gray-500">
            {{ selectedPaddleProductCurrencyCode }}
          </span>
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
            <span class="text-sm text-gray-500">No expiry</span>
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
              class="inline-flex items-center justify-center px-6 py-3 border border-transparent text-base font-medium rounded-md text-indigo-700 bg-indigo-100 hover:bg-indigo-200 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 relative"
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
import ApplicationLogo from '@/Jetstream/ApplicationLogo'
import EmojiFlag from '@/Components/EmojiFlag'
import AjaxSingleButtonForm from '@/Components/AjaxSingleButtonForm'
import { join, map, nth, isEmpty, get, find } from 'lodash-es'
import { jsonp } from 'vue-jsonp'
import { formatCurrency } from '@/utils/strings'

export default {
  name: 'GetMoreCredits',
  components: {
    Tabs,
    TabPane,
    IconCredits,
    IconHeroiconsSpinner,
    IconHeroiconsMediumCheck,
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

      const amount = get(this.selectedPaddleProductPriceData, 'price.gross')

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

      this.isFetchingPaddleProductPrices = true
      this.errorFetchingPaddleProductPrices = false

      try {
        const response = await jsonp('https://checkout.paddle.com/api/2.0/prices', {
          product_ids: join(this.paddleProductIds, ', ')
        })
        this.paddleProductPrices = response.response
      } catch (e) {
        this.errorFetchingPaddleProductPrices = true
      } finally {
        this.isFetchingPaddleProductPrices = false
      }
    }
  }
}
</script>
