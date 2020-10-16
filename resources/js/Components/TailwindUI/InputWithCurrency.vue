<template>
  <div>
    <label :for="inputLabelId" class="block text-sm leading-5 font-medium text-gray-600">
      {{ label }}
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
        :options="{
          numeral: true,
          numeralPositiveOnly: true,
          numeralThousandsGroupStyle: 'thousand'
        }"
        class="form-input block w-full pl-7 pr-12 sm:text-sm sm:leading-5"
        placeholder="0.00"
        />

      <div class="absolute inset-y-0 right-0 flex items-center">
        <select
          v-model="selectedCurrencyCode"
          aria-label="Currency"
          :disabled="!canSwitchCurrency"
          :style="selectCurrencyStyleObject"
          :class="canSwitchCurrency ? `pr-7 cursor-pointer` : `pr-2`"
          class="form-select h-full py-0 pl-2 border-transparent bg-transparent text-gray-500 sm:text-sm sm:leading-5">

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
      default: () => [
        {
          code: 'GBP',
          symbol_native: '£',
          emoji: '🇬🇧'
        },
        {
          code: 'USD',
          symbol_native: '$',
          emoji: '🇺🇸'
        },
        {
          code: 'EUR',
          symbol_native: '€',
          emoji: '🇪🇺'
        },
        {
          code: 'AUD',
          symbol_native: '$',
          emoji: '🇦🇺'
        },
        {
          code: 'CAD',
          symbol_native: '$',
          emoji: '🇨🇦'
        },
        {
          code: 'NZD',
          symbol_native: '$',
          emoji: '🇳🇿'
        },
      ]
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
    inputLabelId () {
      return snakeCase(this.label)
    },
    selectedCurrency () {
      return find(this.currencies, { code: this.selectedCurrencyCode })
    },
    selectCurrencyStyleObject () {
      if (this.canSwitchCurrency) return {}

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
