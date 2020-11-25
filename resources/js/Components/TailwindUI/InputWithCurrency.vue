<template>
  <div>
    <label :for="inputLabelId" class="block text-sm leading-5 font-medium text-gray-600">
      {{ label }}

      <slot name="label-append" />
    </label>
    <div class="mt-1 relative rounded-md shadow-sm">
      <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
        <span class="text-gray-500 sm:text-sm sm:leading-5">
          {{ selectedCurrency.symbol_native }}
        </span>
      </div>

      <InputCleave
        :id="inputLabelId"
        v-model="amount"
        :editable="editable"
        :options="{
          numeral: true,
          numeralPositiveOnly: true,
          numeralThousandsGroupStyle: 'thousand'
        }"
        :class="{ 'cursor-not-allowed': !editable }"
        class="focus:ring-primary-yellow-500 focus:border-primary-yellow-500 block w-full pl-7 pr-12 sm:text-sm border-gray-300 rounded-md"
        placeholder="0.00"
        />

      <div class="absolute inset-y-0 right-0 flex items-center">
        <select
          v-model="selectedCurrencyCode"
          aria-label="Currency"
          :disabled="isCurrencySwitchDisabled"
          :style="selectCurrencyStyleObject"
          :class="isCurrencySwitchDisabled ? `pr-2` : `pr-7 cursor-pointer`"
          class="focus:ring-primary-yellow-500 focus:border-primary-yellow-500 h-full py-0 pl-2 border-transparent bg-transparent text-gray-500 sm:text-sm rounded-md">

          <option
            v-for="currency in currencies"
            :key="currency.code"
            :value="currency.code">
            {{ currency.code }} {{ currency.emoji }} &nbsp;
          </option>
        </select>
      </div>
    </div>
  </div>
</template>

<script>
import InputCleave from '@/Components/InputCleave'
import { isNil, find, get, debounce, snakeCase } from 'lodash-es'
import { defaultCurrencies } from '@/data/currencies'

export default {
  components: {
    InputCleave,
  },
  props: {
    label: {
      type: String,
      default: 'Price'
    },
    defaultCurrencyCode: {
      type: String,
      default: 'GBP',
    },
    currencies: {
      type: Array,
      default: () => [...defaultCurrencies]
    },
    currencyModel: {
      type: String
    },
    amountModel: {
      type: [ String, Number ]
    },
    notifyChangeDebounceMs: {
      type: Number,
      default: 1000
    },
    editable: {
      type: Boolean,
      default: true
    },
    canSwitchCurrency: {
      type: Boolean,
      default: true
    },
    min: Number,
    max: Number
  },
  created () {
    this.notifyChange = debounce((vm) => {
      vm.$emit('changed')
    }, this.notifyChangeDebounceMs)
  },
  computed: {
    isCurrencySwitchDisabled () {
      return !this.editable || !this.canSwitchCurrency
    },
    inputLabelId () {
      return snakeCase(this.label)
    },
    selectedCurrency () {
      return find(this.currencies, { code: this.selectedCurrencyCode })
    },
    selectCurrencyStyleObject () {
      if (!this.isCurrencySwitchDisabled) return {}

      return {
        backgroundImage: 'none'
      }
    },
    amount: {
      get () {
        return this.amountModel
      },
      set (value) {
        this.$emit('update:amountModel', value)
        this.notifyChange(this)
      }
    },
    selectedCurrencyCode: {
      get () {
        if (isNil(this.currencyModel)) {
          return this.defaultCurrencyCode
        }

        return this.currencyModel
      },
      set (value) {
        this.$emit('update:currencyModel', value)
        this.notifyChange(this)
      }
    }
  },
}
</script>
