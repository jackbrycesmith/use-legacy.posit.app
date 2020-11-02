<template>
  <div class="relative bg-cool-gray-300 flex flex-col items-center" style="height: 65vh; border-top-left-radius: 50% 20%; border-top-right-radius: 50% 20%;">

    <!-- Reiterate proposal name... -->
    <span class="max-w-xs px-5 -mt-7">
      <span class="inline-flex items-center px-2.5 py-0.5 rounded-md text-sm font-medium leading-5 bg-yellow-100 text-yellow-800 mt-15 max-w-full">
        <span class="truncate">{{ proposalTitleBadgeText }}</span>
      </span>
    </span>

    <div class="mt-4 flex flex-col">
      <span class="text-base leading-6 font-medium text-center text-gray-500 uppercase">Total</span>
      <div class="flex items-center justify-center text-5xl leading-none font-extrabold text-gray-900">
        <span>
          {{ proposal.total_amount_display_format }}
        </span>
        <span class="ml-3 text-xl leading-7 font-medium text-gray-500">
          {{ proposal.value_currency_code }}
        </span>
      </div>
    </div>

    <div class="mt-4 relative max-w-screen-xl mx-auto px-4 sm:px-6 lg:px-8 lg:mt-5">
        <div class="max-w-md mx-auto lg:max-w-5xl">
            <div class="rounded-lg px-6 sm:p-10 lg:flex lg:items-center">
                <div class="flex-1">
                    <div class="mt-4 text-lg leading-7 text-gray-600 text-center">
                      Please make a payment of <strong class="font-semibold text-gray-900">{{ proposal.deposit_amount_display_format }}</strong> to accept.
                    </div>
                </div>
            </div>
        </div>
    </div>

    <span class="inline-flex rounded-md shadow-sm mt-4">
      <button type="submit" class="bg-gray-800 border border-transparent rounded-md py-2 px-4 inline-flex justify-center text-sm leading-5 font-medium text-white hover:bg-gray-700 focus:outline-none focus:border-gray-900 focus:shadow-outline-gray active:bg-gray-900 transition duration-150 ease-in-out">

        <IconHeroiconsSmallCheck class="h-5 w-5 mr-1.5" />

        <span>Accept with Payment</span>
      </button>
    </span>

  </div>
</template>

<script>
import Proposal from '@/models/Proposal'
import ContentEditable from '@/Components/ContentEditable'
import IconHeroiconsMediumArrowNarrowDown from '@/Icons/IconHeroiconsMediumArrowNarrowDown'
import IconHeroiconsSmallCheck from '@/Icons/IconHeroiconsSmallCheck'
import { isNil } from 'lodash-es'

export default {
  props: {
    proposal: { type: Object }
  },
  components: {
    ContentEditable,
    IconHeroiconsMediumArrowNarrowDown,
    IconHeroiconsSmallCheck
  },
  data () {
    return {
      closingTitle: 'Next Steps'
    }
  },
  computed: {
    proposalTitleBadgeText () {
      return this.proposal?.name ?? 'Proposal'
    }
  },
  watch: {
    proposal: {
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
        if (value !== this.proposal.closing_title) {
          this.proposal.closing_title = value
          this.$emit('update:proposal', this.proposal)
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
