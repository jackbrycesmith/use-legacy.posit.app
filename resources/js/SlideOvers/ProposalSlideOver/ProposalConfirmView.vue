<template>
  <div class="flex-1 flex flex-col justify-between">
    <div class="px-4 sm:px-6">
      <!-- Cannot Publish / Validation Errors -->
      <template v-if="state.matches('confirmPublishView.cannotPublish')">
        <div class="text-center mt-10">
          <IconHeroiconsMediumExclamationCircle
            class="h-20 w-20 text-primary-yellow-600 inline-block animate-wave"
          />
        </div>

        <h3 class="mt-5 text-center text-2xl font-medium text-gray-800">
          Publish
        </h3>

        <p class="text-gray-500 text-center text-sm">
          Almost there! Please fix before publishing…
        </p>

        <!-- Points to fix before publishing... -->
        <div
          v-if="proposal.has_things_to_fix_before_publish"
          class="mt-5 text-sm leading-5 text-gray-600 sm:flex sm:items-center justify-center">
          <ul>
            <li
              v-for="(point, i) in proposal.to_fix_before_publish"
              :key="point.text"
              class="flex gap-1"
              :class="{ 'mt-0.5': i > 0 }">

              <IconHeroiconsMediumExclamation
                v-if="point.icon === 'warning'"
                class="h-5 w-5 text-primary-yellow-600 inline-block align-text-top flex-shrink-0" />

              <span class="align-middle font-medium text-primary-yellow-600" v-html="point.text"/>
            </li>
          </ul>
        </div>

        <div class="text-center mt-5">
          <span class="inline-flex rounded-md shadow-sm">
            <button @click.prevent="$emit('back-to-default-view')" type="submit" class="inline-flex justify-center py-2 px-4 border border-transparent text-sm leading-5 font-medium rounded-md text-primary-yellow-900 bg-primary-yellow-400 hover:bg-primary-yellow-300 focus:outline-none focus:border-primary-yellow-500 focus:shadow-outline-primary-yellow focus:bg-primary-yellow-300 active:bg-primary-yellow-300 transition duration-150 ease-in-out">
              Ok, let me fix
            </button>
          </span>
        </div>
      </template>

      <!-- Confirm Publish e.g. credit deduction -->
      <template v-if="state.matches('confirmPublishView.canPublish')">
        <h3 class="mt-10 text-center text-2xl font-medium text-gray-800">
          Confirm Publish
        </h3>

        <!-- TODO credits depletion -->
        <div class="bg-white overflow-hidden shadow rounded-lg mt-3 mb-10">
          <div class="px-4 py-5 sm:p-6">
            <!-- Content goes here -->
            <div class="grid grid-cols-3 grid-rows-2">

              <div class="col-span-3 flex flex-col justify-center items-center">

                <div class="flex flex-col justify-center items-center">
                  <BadgeWithDot size="large" color="yellow">
                    <template #dot>
                      <IconCredits class="h-5 w-5 -ml-1 mr-1.5 text-yellow-400" />
                    </template>

                    1 Credit
                  </BadgeWithDot>

                  <span class="text-xs text-gray-500 mt-3">
                    Will be used to publish this proposal
                  </span>
                </div>

              </div>

              <!-- Credits Now -->
              <div class="flex flex-col items-center justify-center">
                <span class="text-2xl">&infin;</span>
                <span class="text-sm text-gray-500 uppercase">Now</span>
              </div>

              <!-- Arrow -->
              <div class="flex items-center justify-center">
                <IconHeroiconsMediumArrowNarrowRight class="w-7 h-7" />
              </div>

              <!-- Credits After -->
              <div class="flex flex-col items-center justify-center">
                <span class="text-2xl">&infin;</span>
                <span class="text-sm text-gray-500 uppercase">After</span>
              </div>

            </div>


          </div>
        </div>


        <div class="text-center">
          <span
            class="inline-flex rounded-md shadow-sm">
            <button
              @click.prevent="$emit('publish')"
              type="submit"
              :disabled="state.matches('confirmPublishView.canPublish.publishing')"
              :class="{ 'cursor-wait': state.matches('confirmPublishView.canPublish.publishing') }"
              class="inline-flex justify-center py-2 px-4 border border-transparent text-sm leading-5 font-medium rounded-md text-primary-yellow-900 bg-primary-yellow-400 hover:bg-primary-yellow-300 focus:outline-none focus:border-primary-yellow-500 focus:shadow-outline-primary-yellow focus:bg-primary-yellow-300 active:bg-primary-yellow-300 transition duration-150 ease-in-out relative"
              style="min-width: 200px;">

              <span
                v-if="state.matches('confirmPublishView.canPublish.publishing')"
                class="absolute left-0 inset-y-0 flex items-center pl-3">
                <IconHeroiconsSpinner
                  class="h-5 w-5 text-primary-yellow-100" />
              </span>

              Publish Now
            </button>
          </span>
        </div>

        <template v-if="state.matches('confirmPublishView.canPublish.ready') || state.matches('confirmPublishView.canPublish.publishError')">
          <div class="mt-6 relative">
            <div class="absolute inset-0 flex items-center">
              <div class="w-full border-t border-gray-300"></div>
            </div>
            <div class="relative flex justify-center text-sm leading-5">
              <span class="px-2 bg-white text-gray-500">
                OR
              </span>
            </div>
          </div>

          <div class="text-center mt-5">
            <span class="inline-flex rounded-md shadow-sm">
              <button @click.prevent="$emit('back-to-default-view')" type="button" class="inline-flex items-center px-4 py-2 border border-gray-300 text-sm leading-5 font-medium rounded-md text-gray-700 bg-white hover:text-gray-500 focus:outline-none focus:border-blue-300 focus:shadow-outline-blue active:text-gray-800 active:bg-gray-50 transition ease-in-out duration-150">
                Go back
              </button>
            </span>
          </div>
        </template>

      </template>

      <template v-if="state.matches('confirmPublishView.publishSuccess')">
        <h3 class="mt-10 text-center text-2xl font-medium text-gray-800">
          Published
        </h3>

        <div class="text-center mt-5">
          <IconHeroiconsMediumCheck class="h-20 w-20 text-primary-yellow-400 inline-block" />
        </div>

        <!-- Public URL Share -->
        <div class="space-y-1 mt-10">
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
                type="text"
                id="public_link"
                class="focus:ring-primary-yellow-500 focus:border-primary-yellow-500 block w-full rounded-none rounded-l-md pl-10 sm:text-sm border-gray-300"
                disabled>
            </div>
            <button
              ref="copyButton"
              @click.prevent="handleCopyPublicLinkHit"
              class="-ml-px relative inline-flex items-center space-x-2 px-4 py-2 border border-gray-300 text-sm font-medium rounded-r-md text-gray-700 bg-gray-50 hover:bg-white focus:outline-none focus:ring-1 focus:ring-primary-yellow-500 focus:border-primary-yellow-500">

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

        <!-- Access Code  -->
        <div class="space-y-1 mt-8">
          <label for="access_code_link" class="block text-sm font-medium leading-5 text-gray-600 truncate">
            Access Code for {{ proposal.recipient_name }}
          </label>

          <div class="mt-1 flex rounded-md shadow-sm">
            <div class="relative flex-grow focus-within:z-10">
              <input
                v-model="proposal.recipient_access_code"
                id="access_code_link"
                type="text"
                class="focus:ring-primary-yellow-500 focus:border-primary-yellow-500 block w-full rounded-none rounded-l-md sm:text-sm border-gray-300 font-mono"
                disabled>
            </div>
            <button
              ref="copyAccessCodeButton"
              @click.prevent="handleCopyAccessCodeHit"
              class="-ml-px relative inline-flex items-center space-x-2 px-4 py-2 border border-gray-300 text-sm font-medium rounded-r-md text-gray-700 bg-gray-50 hover:bg-white focus:outline-none focus:ring-1 focus:ring-primary-yellow-500 focus:border-primary-yellow-500">

              <SuccessFlashSwitcher
                ref="copyAccessCodeIcon"
                :to-blur-after-success-el="$refs['copyAccessCodeButton']">

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

        <!-- Convenient message -->
        <div class="space-y-1 mt-8 mb-20">
          <label for="copy_message" class="block text-sm font-medium leading-5 text-gray-600 truncate">
            Example Message
          </label>

          <div class="relative">
            <textarea
              v-model="proposal.convenient_copyable_recipient_access_message"
              readonly disabled rows="7"
              class="max-w-lg shadow-sm block w-full focus:ring-primary-yellow-500 focus:border-primary-yellow-500 sm:text-sm border-gray-300 rounded-md resize-none" />

            <div class="absolute bottom-0 right-0">
              <button
                ref="copyMessageButton"
                @click.prevent="handleCopyMessageHit"
                class="-ml-px relative inline-flex items-center px-4 py-2 border border-gray-300 text-sm leading-5 font-medium rounded-tl-md rounded-tr-none rounded-br-md rounded-bl-none text-gray-700 bg-gray-50 hover:text-gray-500 hover:bg-white focus:outline-none focus:ring-1 focus:ring-primary-yellow-500 focus:border-primary-yellow-500">

                <SuccessFlashSwitcher
                  ref="copyMessageIcon"
                  :to-blur-after-success-el="$refs['copyMessageButton']">

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
        </div>

      </template>
    </div>
  </div>
</template>

<script>
import ApplicationMark from '@/Jetstream/ApplicationMark'
import IconCredits from '@/Icons/IconCredits'
import IconHeroiconsSmallExternalLink from '@/Icons/IconHeroiconsSmallExternalLink'
import IconHeroiconsMediumExclamation from '@/Icons/IconHeroiconsMediumExclamation'
import IconHeroiconsMediumArrowNarrowRight from '@/Icons/IconHeroiconsMediumArrowNarrowRight'
import IconHeroiconsMediumExclamationCircle from '@/Icons/IconHeroiconsMediumExclamationCircle'
import IconHeroiconsSmallCheck from '@/Icons/IconHeroiconsSmallCheck'
import IconHeroiconsMediumCheck from '@/Icons/IconHeroiconsMediumCheck'
import IconHeroiconsSpinner from '@/Icons/IconHeroiconsSpinner'
import SuccessFlashSwitcher from '@/Components/SuccessFlashSwitcher'
import BadgeWithDot from '@/Components/TailwindUI/BadgeWithDot'
import copy from 'clipboard-copy'

export default {
  components: {
    ApplicationMark,
    BadgeWithDot,
    IconCredits,
    IconHeroiconsSmallExternalLink,
    IconHeroiconsMediumExclamation,
    IconHeroiconsMediumArrowNarrowRight,
    IconHeroiconsMediumExclamationCircle,
    IconHeroiconsSmallCheck,
    IconHeroiconsSpinner,
    IconHeroiconsMediumCheck,
    SuccessFlashSwitcher,
  },
  props: {
    proposal: { type: Object },
    state: { type: Object }
  },
  methods: {
    async handleCopyPublicLinkHit () {
      await copy(this.proposal.route_pub_proposal_view_link)
      this.$refs.copyIcon.success()
    },
    async handleCopyAccessCodeHit () {
      await copy(this.proposal.recipient_access_code)
      this.$refs.copyAccessCodeIcon.success()
    },
    async handleCopyMessageHit () {
      await copy(this.proposal.convenient_copyable_recipient_access_message)
      this.$refs.copyMessageIcon.success()
    },
  },
}
</script>
