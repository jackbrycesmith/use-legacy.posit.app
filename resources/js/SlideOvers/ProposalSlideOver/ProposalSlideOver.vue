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
            <svg class="h-5 w-5 ml-1 text-primary-yellow-500 animate-wave" fill="none" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" viewBox="0 0 24 24" stroke="currentColor"><path d="M15 19l-7-7 7-7"></path></svg>
          </button>
        </div>
      </div>
    </transition>

    <BaseSlideOver
      :is-visible.sync="isVisible"
      :is-rounded="true"
      :is-divide-y="false"
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
            class="absolute h-15 left-0 right-0 grid grid-cols-3 items-center justify-evenly z-10"
            style="bottom: -1.875rem;">

            <!-- Creator -->
            <div class="relative col-span-1">

              <!-- Creator Hint -->
              <div class="absolute w-full flex items-center justify-center text-xs tracking-wider uppercase text-primary-yellow-700 font-semibold -mt-8">
                Creator
              </div>

              <div class="flex space-x-2 justify-center">
                <span :title="proposal.creator_name">

                  <div class="inline-flex justify-center items-center h-10 w-10 bg-white text-orange-400 rounded-full shadow-md select-none">
                    <template v-if="proposal.creator_has_profile_photo">
                      <img :src="proposal.creator_profile_photo_url" alt="Creato profile photo" class="rounded-full h-9 w-9 object-cover">
                    </template>
                    <template v-else>
                      {{ proposal.creator_initials }}
                    </template>
                  </div>
                </span>
              </div>
            </div>

            <!-- Status -->
            <div class="flex col-span-1 justify-center">
              <BadgeWithDot
                custom-badge-class="bg-white text-primary-yellow-800 shadow-md"
                custom-dot-class="text-primary-yellow-400">
                {{ proposal.status_name | titleCase }}
              </BadgeWithDot>
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
        <div class="relative">
          <transition
            enter-active-class="transform transition ease-in-out duration-500 sm:duration-700"
            :enter-class="enterClass"
            :enter-to-class="enterToClass"
            leave-active-class="transform transition ease-in-out duration-500 sm:duration-700"
            :leave-class="leaveClass"
            :leave-to-class="leaveToClass"
          >
            <keep-alive include="ProposalTweakView">
              <component
                :is="bodyComponent"
                :proposal="proposal"
                :state="contentCurrentState"
                :proposal-editor-machine-state="proposalEditorMachineState"
                class="absolute inset-0"
                @back-to-default-view="contentMachineService.send('BACK_TO_DEFAULT_VIEW')"
                @publish="contentMachineService.send('PUBLISH')" />
            </keep-alive>
          </transition>
        </div>
      </template>

      <template #footer>
        <div class="overflow-hidden flex flex-shrink-0">

          <transition
            enter-active-class="transform transition ease-in-out duration-500 sm:duration-700"
            :enter-class="footerEnterClass"
            :enter-to-class="footerEnterToClass"
            leave-active-class="transform transition ease-in-out duration-500 sm:duration-700"
            :leave-class="footerLeaveClass"
            :leave-to-class="footerLeaveToClass">
            <div v-if="isDefaultView || isSuccessPublishView" class="px-4 py-4 flex flex-1 border-t border-gray-200">
              <template v-if="isDefaultView">
                <span class="inline-flex rounded-md shadow-sm flex-1">
                  <button @click.prevent="contentMachineService.send('PREPARE_TO_PUBLISH')" type="submit" class="inline-flex justify-center py-2 px-4 border border-transparent text-sm leading-5 font-medium rounded-md text-primary-yellow-900 bg-primary-yellow-400 hover:bg-primary-yellow-300 focus:outline-none focus:border-primary-yellow-500 focus:shadow-outline-primary-yellow focus:bg-primary-yellow-300 active:bg-primary-yellow-300 transition duration-150 ease-in-out flex-1">
                    {{ contentContext.isPublished ? 'View Access Details »' : 'Ready to Publish »' }}
                  </button>
                </span>
              </template>

              <template v-if="isSuccessPublishView">
                <span class="inline-flex rounded-md shadow-sm flex-1">
                  <button @click.prevent="contentMachineService.send('BACK_TO_DEFAULT_VIEW')" type="submit" class="inline-flex justify-center py-2 px-4 border border-transparent text-sm leading-5 font-medium rounded-md text-primary-yellow-900 bg-primary-yellow-400 hover:bg-primary-yellow-300 focus:outline-none focus:border-primary-yellow-500 focus:shadow-outline-primary-yellow focus:bg-primary-yellow-300 active:bg-primary-yellow-300 transition duration-150 ease-in-out flex-1">
                    Back to details
                  </button>
                </span>
              </template>
            </div>
          </transition>

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
import { interpret, assign } from 'xstate'
import ApplicationLogo from '@/Jetstream/ApplicationLogo'
import BaseSlideOver from '@/SlideOvers/BaseSlideOver'
import ProposalRecipientSelector from '@/Components/ProposalRecipientSelector'
import BadgeWithDot from '@/Components/TailwindUI/BadgeWithDot'
import {
  proposalSlideOverContentMachine,
  // ProposalConfirmView, // IDK why this isn't working; throwing some error in the console so will import manually
  // ProposalTweakView
} from '@/SlideOvers/ProposalSlideOver'
import ProposalConfirmView from '@/SlideOvers/ProposalSlideOver/ProposalConfirmView'
import ProposalTweakView from '@/SlideOvers/ProposalSlideOver/ProposalTweakView'

export default {
  components: {
    ApplicationLogo,
    BadgeWithDot,
    BaseSlideOver,
    ProposalRecipientSelector,
    ProposalTweakView,
    ProposalConfirmView,
  },
  props: {
    proposal: { type: Object },
    proposalEditorMachineState: {}
  },
  created () {
    this.contentMachineService
      .onTransition(state => {
        // Update the currentState state component data property with the next state
        this.contentCurrentState = state
        // Update the context component data property with the updated context
        this.contentContext = state.context
      })
      .start()

    this.setupInitialMachineContext()
  },
  data () {
    const machine = proposalSlideOverContentMachine.withConfig({
      services: {
        'publishAction': this.publishAction
      }
    })

    return {
      isVisible: false,
      contentMachineService: interpret(machine, { devTools: true }),
      contentCurrentState: machine.initialState,
      contentContext: machine.context
    }
  },
  computed: {
    isDefaultView () {
      return this.contentCurrentState.matches('defaultView')
    },
    isSuccessPublishView () {
      return this.contentCurrentState.matches('confirmPublishView.publishSuccess')
    },
    bodyComponent () {
      return this.isDefaultView ? ProposalTweakView : ProposalConfirmView
    },
    leaveClass () {
      return !this.isDefaultView ? 'translate-x-0' : 'translate-x-0'
    },
    leaveToClass () {
      return !this.isDefaultView ? '-translate-x-full' : 'translate-x-full'
    },
    enterClass () {
      return !this.isDefaultView ? 'translate-x-full' : '-translate-x-full'
    },
    enterToClass () {
      return !this.isDefaultView ? 'translate-x-0' : 'translate-x-0'
    },
    footerLeaveClass () {
      return !this.isDefaultView ? 'translate-x-0' : 'translate-x-0'
    },
    footerLeaveToClass () {
      return !this.isDefaultView ? '-translate-x-full' : '-translate-x-full'
    },
    footerEnterClass () {
      return !this.isDefaultView ? '-translate-x-full' : '-translate-x-full'
    },
    footerEnterToClass () {
      return !this.isDefaultView ? 'translate-x-0' : 'translate-x-0'
    },
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
    setupInitialMachineContext () {
      this.handleHasThingsToPublishEvent(this.proposal.has_things_to_fix_before_publish)
      this.handleHasBeenPublishedEvent(this.proposal.has_been_published)
    },
    handleHasThingsToPublishEvent (value) {
      const event = value ? 'CANNOT_PUBLISH' : 'CAN_PUBLISH'
      this.contentMachineService.send(event)
    },
    handleHasBeenPublishedEvent (value) {
      const event = value ? 'IS_PUBLISHED' : 'IS_NOT_PUBLISHED'
      this.contentMachineService.send(event)
    },
    async publishAction () {
      await this.proposal.publish()
    }
  },
  watch: {
    'proposal.has_things_to_fix_before_publish': {
      handler (value) {
        this.handleHasThingsToPublishEvent(value)
      }
    }
  },
}
</script>
