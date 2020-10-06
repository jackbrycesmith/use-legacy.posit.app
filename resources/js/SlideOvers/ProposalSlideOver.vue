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
            <!-- Section -->
            <div class="space-y-6 pt-6 pb-5 mt-5">

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

            <!-- Another section -->
            <div class="space-y-4 pt-4 pb-6">
              <!-- Next Actions -->
              <div class="space-y-2">
<!--                 <h3 class="text-md font-medium leading-5 text-gray-900">
                  Define next steps/actions...
                </h3> -->


              </div>
            </div>

          </div>
        </div>
      </template>

      <template #footer>
        <span class="inline-flex rounded-md shadow-sm">
          <button @click.prevent="$emit('updatePressed')" type="submit" class="inline-flex justify-center py-2 px-4 border border-transparent text-sm leading-5 font-medium rounded-md text-primary-yellow-900 bg-primary-yellow-400 hover:bg-primary-yellow-300 focus:outline-none focus:border-primary-yellow-500 focus:shadow-outline-primary-yellow active:bg-primary-yellow-500 transition duration-150 ease-in-out">
            Publish Proposal
          </button>
        </span>

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
import BaseSlideOver from '@/SlideOvers/BaseSlideOver'
import ProposalRecipientSelector from '@/Components/ProposalRecipientSelector'
import ProposalThemeBlock from '@/Components/ProposalThemeBlock'
import IconHeroiconsMediumInformationCircle from '@/Icons/IconHeroiconsMediumInformationCircle'
import BadgeWithDotSmall from '@/Components/TailwindUI/BadgeWithDotSmall'

export default {
  components: {
    ApplicationLogo,
    BadgeWithDotSmall,
    BaseSlideOver,
    ProposalRecipientSelector,
    ProposalThemeBlock,
    IconHeroiconsMediumInformationCircle
  },
  props: {
    proposal: { type: Object }
  },
  data: () => ({
    isVisible: false
  }),
  methods: {
    handleExtraCloseButtonHit () {
      this.isVisible ? this.hide() : this.show()
    },
    show () {
      this.isVisible = true
    },
    hide () {
      this.isVisible = false
    }
  }
}
</script>
