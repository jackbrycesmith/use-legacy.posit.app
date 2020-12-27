<template>
  <div
    class="relative bg-blue-gray-200 flex flex-col items-center justify-center"
    style="height: 65vh; border-top-left-radius: 50% 20%; border-top-right-radius: 50% 20%;">

    <!-- Reiterate posit name... -->
    <span class="max-w-xs px-5 mt-7 absolute top-0">
      <span class="inline-flex items-center px-2.5 py-0.5 rounded-md text-sm font-medium leading-5 bg-yellow-100 text-yellow-800 mt-15 max-w-full">
        <span class="truncate">{{ positTitleBadgeText }}</span>
      </span>
    </span>

    <!-- Stuff to include if it includes price -->
    <template v-if="posit.includes_pricing">

      <div class="flex flex-col">
        <span class="text-base leading-6 font-medium text-center text-gray-500 uppercase">Total</span>
        <div class="flex items-center justify-center text-5xl leading-none font-extrabold text-gray-900">
          <span>
            {{ posit.total_amount_display_format }}
          </span>
          <span class="ml-3 text-xl leading-7 font-medium text-gray-500">
            {{ posit.value_currency_code }}
          </span>
        </div>
      </div>

      <template v-if="!posit.is_accepted">
        <div class="mt-4 relative max-w-screen-xl mx-auto px-4 sm:px-6 lg:px-8 lg:mt-5">
            <div class="max-w-md mx-auto lg:max-w-5xl">
                <div class="rounded-lg px-6 sm:p-10 lg:flex lg:items-center">
                    <div class="flex-1">
                        <div class="mt-4 text-lg leading-7 text-gray-600 text-center">
                          Please make a payment of <strong class="font-semibold text-gray-900">{{ posit.deposit_amount_display_format }}</strong> to accept.
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <PositAccept
          :accepting="publicPositMachineState.matches('unlocked.published.acceptingWithPayment')"
          class="mt-4"
          @click.native="$emit('accept-with-payment')"/>
      </template>

      <PositAcceptedMeta v-if="posit.is_accepted" />

    </template>

    <!-- Simple accept_only stuff -->
    <template v-else>
      <PositAccept
        v-if="!posit.is_accepted"
        :accepting="publicPositMachineState.matches('unlocked.published.acceptingWithPayment')"/>

      <PositAcceptedMeta v-if="posit.is_accepted" />
    </template>

  </div>
</template>

<script>
import Posit from '@/models/Posit'
import ContentEditable from '@/Components/ContentEditable'
import IconHeroiconsMediumArrowNarrowDown from '@/Icons/IconHeroiconsMediumArrowNarrowDown'
import IconHeroiconsSmallCheck from '@/Icons/IconHeroiconsSmallCheck'
import IconHeroiconsSpinner from '@/Icons/IconHeroiconsSpinner'
import PositAcceptedMeta from '@/Components/PositAcceptedMeta'
import { isNil } from 'lodash-es'

export default {
  props: {
    posit: { type: Object },
    publicPositMachineState: { type: Object, required: true },
  },
  components: {
    ContentEditable,
    IconHeroiconsMediumArrowNarrowDown,
    IconHeroiconsSmallCheck,
    IconHeroiconsSpinner,
    PositAcceptedMeta
  },
  data () {
    return {

    }
  },
  computed: {
    positTitleBadgeText () {
      return this.posit?.name ?? 'Posit'
    }
  },
  watch: {

  },
  methods: {
  }
}
</script>
