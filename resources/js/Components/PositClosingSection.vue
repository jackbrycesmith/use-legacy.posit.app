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

        <PositAccept class="mt-4" />
      </template>

      <PositAcceptedMeta v-if="posit.is_accepted" />

    </template>

    <!-- Simple accept_only stuff -->
    <template v-else>
      <PositAccept v-if="!posit.is_accepted" />

      <PositAcceptedMeta v-if="posit.is_accepted" />
    </template>

  </div>
</template>

<script>
import Posit from '@/models/Posit'
import ContentEditable from '@/Components/ContentEditable'
import IconHeroiconsMediumArrowNarrowDown from '@/Icons/IconHeroiconsMediumArrowNarrowDown'
import PositAcceptedMeta from '@/Components/PositAcceptedMeta'
import PositAccept from '@/Components/PositAccept'
import IconHeroiconsSmallCheck from '@/Icons/IconHeroiconsSmallCheck'
import { isNil } from 'lodash-es'

export default {
  props: {
    posit: { type: Object }
  },
  components: {
    ContentEditable,
    IconHeroiconsMediumArrowNarrowDown,
    PositAccept,
    PositAcceptedMeta,
    IconHeroiconsSmallCheck
  },
  data () {
    return {
      closingTitle: 'Next Steps'
    }
  },
  computed: {
    positTitleBadgeText () {
      return this.posit?.name ?? 'Posit'
    }
  },
  watch: {
    posit: {
      immediate: true,
      handler (value) {
        if (isNil(value.closing_title)) return

        if (value.closing_title !== this.closingTitle) {
          this.closingTitle = value.closing_title
        }
      }
    },
    title: {
      closingTitle (value) {
        if (value !== this.posit.closing_title) {
          this.posit.closing_title = value
          this.$emit('update:posit', this.posit)
        }
      }
    }
  },
  methods: {
    handleEditTitleDone (value) {
      this.$emit('edit-title-done', value)
    }
  }
}
</script>
