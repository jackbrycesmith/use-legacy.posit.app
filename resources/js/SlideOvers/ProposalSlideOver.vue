<template>
  <fragment>
    <!-- Reveal hidden proposal slide over trigger... -->
    <transition
      enter-active-class="ease-in-out duration-300"
      enter-class="opacity-0"
      enter-to-class="opacity-100"
      leave-active-class="ease-in-out duration-75"
      leave-class="opacity-100"
      leave-to-class="opacity-0">
      <div
        v-show="!isVisible"
        class="absolute h-full right-0 top-0">
        <div class="fixed h-screen">
          <button
            @click="handleExtraCloseButtonHit"
            class="absolute bottom-0 top-0 right-0 bg-white inline-block h-20 my-auto rounded-l-full w-7 shadow focus:outline-none focus:shadow-xl">
            <svg class="h-5 w-5 ml-1 text-primary-yellow-500 animate-pulse" fill="none" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" viewBox="0 0 24 24" stroke="currentColor"><path d="M15 19l-7-7 7-7"></path></svg>
          </button>
        </div>
      </div>
    </transition>

    <BaseSlideOver
      :is-visible.sync="isVisible"
      :is-rounded="true"
      sticky-footer-class="flex-shrink-0 px-4 py-4 space-x-4 flex justify-end relative bg-primary-yellow-100 rounded-b-lg"
    >

      <template #header="{ handleCloseButtonHit }">
        <header class="space-y-1 py-6 px-4 relative bg-primary-yellow-400 sm:px-6">
          <div class="flex items-center justify-between space-x-3 items-baseline">

            <ApplicationLogo class="h-7 w-auto" />

            <div class="h-7 flex items-center">
              <button @click="handleCloseButtonHit" aria-label="Close panel" class="text-primary-yellow-50 hover:text-primary-yellow-600 focus:outline-none focus:text-primary-yellow-600 transition ease-in-out duration-150">
                <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                </svg>
              </button>
            </div>
          </div>

          <h2 class="text-lg leading-7 font-medium text-primary-yellow-800 truncate mt-0.5">
            » {{ proposal.name }}
          </h2>

          <!-- height increase -->
          <div class="h-10" />

          <!-- Creator / Recipient overlap -->
          <div
            class="absolute h-15 left-0 right-0 grid grid-cols-3 items-center justify-evenly"
            style="bottom: -1.875rem;">

            <!-- Creator -->
            <div class="relative col-span-1">

              <!-- Creator Hint -->
              <div class="absolute w-full flex items-center justify-center text-xs tracking-wider uppercase text-primary-yellow-700 font-semibold -mt-8">
                Creator
              </div>

              <div class="flex space-x-2 justify-center">
                <span :title="proposal.creator_name">
                    <div class="inline-flex justify-center items-center h-10 w-10 bg-white text-primary-yellow-500 rounded-full shadow-md select-none">
                      {{ proposal.creator_name | initials }}
                    </div>
                </span>
              </div>
            </div>

            <!-- Status -->
            <div class="flex col-span-1 justify-center">
              <BadgeWithDotSmall
                custom-badge-class="bg-white text-primary-yellow-800 shadow-md"
                custom-dot-class="text-primary-yellow-400">
                {{ proposal.status_name | titleCase }}
              </BadgeWithDotSmall>
            </div>

            <!-- Recipient -->
            <div class="relative col-span-1">

              <!-- Recipient Hint -->
              <div class="absolute w-full flex items-center justify-center text-xs tracking-wider uppercase text-primary-yellow-700 font-semibold -mt-8">
                Recipient
              </div>

              <div class="flex space-x-2 justify-center">
                <ProposalRecipientSelector
                  :proposal.sync="proposal"
                  :options="proposal.recipient_options"
                />

              </div>
            </div>

          </div>
        </header>
      </template>

      <template #body>
        <div class="flex-1 flex flex-col justify-between">
          <div class="px-4 sm:px-6">
            <Tabs
              :show-tab-text-when-inactive="false"
              active-tab-class="border-b-2 border-primary-yellow-500 text-primary-yellow-600 focus:outline-none focus:text-primary-yellow-800 focus:border-primary-yellow-700"
              active-tab-icon-class="text-primary-yellow-500 group-focus:text-primary-yellow-600"
              tabs-class="mt-8">

              <TabPane title="Design" :icon="designTabIcon">
                <div class="space-y-6 pt-6 pb-5">

                  <!-- Proposal Theme -->
                  <div class="space-y-1">
                    <label for="proposal_theme" class="block text-sm font-medium leading-5 text-gray-600">
                      Theme
                    </label>

                    <!-- Theme list options -->
                    <div class="flex gap-2 items-center">
                      <ProposalThemeBlock name="Cool Grey" class="flex-shrink-0" />
                      <p class="flex-1 text-center text-gray-400 text-xs">
                        <IconHeroiconsMediumInformationCircle class="inline w-5 h-5 align-bottom" />
                        More theme choices coming soon!
                      </p>
                    </div>
                  </div>

                </div>
              </TabPane>

              <TabPane title="Details" :icon="detailsTabIcon">

                  <div class="space-y-6 pt-6 pb-5">

                    <!-- Project Value -->
                    <InputWithCurrency
                      :currency-model.sync="proposal.value_currency_code"
                      :amount-model.sync="proposal.value_amount"
                      :max="999999999"
                      label="Project Value"
                      @changed="handleUpdateProjectValue"
                      class="space-y-1" />

                    <!-- Deposit -->
                    <ProposalDepositConfigure
                      class="space-y-1"
                    />

                  </div>
              </TabPane>
            </Tabs>

          </div>
        </div>
      </template>

      <template #footer>

        <div class="flex-1">
          <!-- Public URL Share -->
          <div class="space-y-1">
            <label for="public_link" class="block text-sm font-medium leading-5 text-gray-600">
              Share Link
            </label>

            <div class="mt-1 flex rounded-md shadow-sm">
              <div class="relative flex-grow focus-within:z-10">
                <a
                  :href="proposal.route_pub_proposal_view_link"
                  title="Visit Public Link Now"
                  rel="noopener noreferrer"
                  target="_blank"
                  class="absolute inset-y-0 left-0 pl-3 flex items-center text-gray-400 outline-none hover:text-primary-yellow-500 focus:text-primary-yellow-500 transition duration-150 ease-in-out">
                  <ApplicationMark class="w-5 h-5" />
                </a>

                <input
                  v-model="proposal.route_pub_proposal_view_link"
                  id="public_link"
                  class="form-input block w-full rounded-none rounded-l-md pl-10 transition ease-in-out duration-150 sm:text-sm sm:leading-5"
                  disabled>
              </div>
              <button ref="copyButton" @click.prevent="handleCopyPublicLinkHit" class="-ml-px relative inline-flex items-center px-4 py-2 border border-gray-300 text-sm leading-5 font-medium rounded-r-md text-gray-700 bg-gray-50 hover:text-gray-500 hover:bg-white focus:outline-none focus:shadow-outline-yellow focus:border-primary-yellow-300 active:bg-gray-100 active:text-gray-700 transition ease-in-out duration-150">

                <SuccessFlashSwitcher
                  ref="copyIcon"
                  :to-blur-after-success-el="$refs['copyButton']">

                  <template #normal>
                    <IconHeroiconsSmallExternalLink class="h-5 w-5 text-gray-400" />
                  </template>

                  <template #success>
                    <IconHeroiconsSmallCheck class="h-5 w-5 text-primary-yellow-400" />
                  </template>
                </SuccessFlashSwitcher>


                <span class="ml-2">Copy</span>

              </button>
            </div>
          </div>

<!--           <span class="inline-flex rounded-md shadow-sm">
            <button @click.prevent="$emit('updatePressed')" type="submit" class="inline-flex justify-center py-2 px-4 border border-transparent text-sm leading-5 font-medium rounded-md text-primary-yellow-900 bg-primary-yellow-400 hover:bg-primary-yellow-300 focus:outline-none focus:border-primary-yellow-500 focus:shadow-outline-primary-yellow active:bg-primary-yellow-500 transition duration-150 ease-in-out">
              Publish Proposal
            </button>
          </span> -->
        </div>



      </template>

      <template #extra-close-button-handling="{ handleCloseButtonHit }">
        <!-- TODO fix shadow -->
        <button
          @click="handleExtraCloseButtonHit"
          class="absolute bottom-0 top-0 -ml-13 bg-white inline-block h-20 my-auto rounded-l-full w-7 focus:outline-none"
          style="margin-left: -1.65rem; box-shadow: -5px 3px 10px -2px rgba(0, 0, 0, 0.1), -5px 10px 10px -5px rgba(0, 0, 0, 0.04);">
          <svg class="h-5 w-5 ml-1 text-primary-yellow-500" fill="none" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" viewBox="0 0 24 24" stroke="currentColor"><path d="M9 5l7 7-7 7"></path></svg>
        </button>
      </template>

    </BaseSlideOver>
  </fragment>
</template>

<script>
import ApplicationLogo from '@/Jetstream/ApplicationLogo'
import ApplicationMark from '@/Jetstream/ApplicationMark'
import BaseSlideOver from '@/SlideOvers/BaseSlideOver'
import ProposalRecipientSelector from '@/Components/ProposalRecipientSelector'
import ProposalThemeBlock from '@/Components/ProposalThemeBlock'
import IconHeroiconsMediumInformationCircle from '@/Icons/IconHeroiconsMediumInformationCircle'
import IconHeroiconsSmallExternalLink from '@/Icons/IconHeroiconsSmallExternalLink'
import IconHeroiconsSmallCheck from '@/Icons/IconHeroiconsSmallCheck'
import IconHeroiconsSmallBriefcase from '@/Icons/IconHeroiconsSmallBriefcase'
import IconHeroiconsSmallAdjustments from '@/Icons/IconHeroiconsSmallAdjustments'
import SuccessFlashSwitcher from '@/Components/SuccessFlashSwitcher'
import BadgeWithDotSmall from '@/Components/TailwindUI/BadgeWithDotSmall'
import InputWithCurrency from '@/Components/TailwindUI/InputWithCurrency'
import ProposalDepositConfigure from '@/Components/ProposalDepositConfigure'
import Tabs from '@/Components/Tabs'
import TabPane from '@/Components/TabPane'
import copy from 'clipboard-copy'

export default {
  components: {
    ApplicationLogo,
    ApplicationMark,
    BadgeWithDotSmall,
    BaseSlideOver,
    ProposalRecipientSelector,
    ProposalThemeBlock,
    IconHeroiconsMediumInformationCircle,
    IconHeroiconsSmallExternalLink,
    IconHeroiconsSmallCheck,
    IconHeroiconsSmallBriefcase,
    IconHeroiconsSmallAdjustments,
    InputWithCurrency,
    SuccessFlashSwitcher,
    ProposalDepositConfigure,
    Tabs,
    TabPane
  },
  props: {
    proposal: { type: Object }
  },
  data: () => ({
    isVisible: false
  }),
  computed: {
    detailsTabIcon () {
      return IconHeroiconsSmallBriefcase
    },
    designTabIcon () {
      return IconHeroiconsSmallAdjustments
    }
  },
  methods: {
    handleExtraCloseButtonHit () {
      this.isVisible ? this.hide() : this.show()
    },
    show () {
      this.isVisible = true
    },
    hide () {
      this.isVisible = false
    },
    async handleCopyPublicLinkHit () {
      await copy(this.proposal.route_pub_proposal_view_link)
      this.$refs.copyIcon.success()
    },
    async handleUpdateProjectValue () {
      try {
        await this.proposal.updateProjectValue()
      } catch (e) {
        // TODO handle update project value error...
      }
    }
  }
}
</script>
