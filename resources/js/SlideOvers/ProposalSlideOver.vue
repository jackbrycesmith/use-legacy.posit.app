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
            class="absolute bottom-0 top-0 right-0 bg-white inline-block h-20 my-auto rounded-l-full w-7 shadow">
            <svg class="h-5 w-5 ml-1 text-orange-400 animate-pulse" fill="none" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" viewBox="0 0 24 24" stroke="currentColor"><path d="M15 19l-7-7 7-7"></path></svg>
          </button>
        </div>
      </div>
    </transition>

    <BaseSlideOver
      :is-visible.sync="isVisible"
      :is-rounded="true"
    >

      <template #header="{ handleCloseButtonHit }">
        <header class="space-y-1 py-6 px-4 bg-orange-300 sm:px-6">
          <div class="flex items-center justify-between space-x-3">
            <h2 class="text-lg leading-7 font-medium text-orange-800 truncate">{{ proposal.name }}</h2>
            <div class="h-7 flex items-center">
              <button @click="handleCloseButtonHit" aria-label="Close panel" class="text-orange-50 hover:text-white transition ease-in-out duration-150">
                <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                </svg>
              </button>
            </div>
          </div>
          <div>
            <p class="text-sm leading-5 text-orange-800 text-opacity-75">
              Get started below by filling in key details, choosing your template & managing access. Publish when ready!
            </p>
          </div>
        </header>
      </template>

      <template #body>
        <div class="flex-1 flex flex-col justify-between">
          <div class="px-4 sm:px-6">
            <!-- Section -->
            <div class="space-y-6 pt-6 pb-5">

              <!-- Organisation Create/Read Access -->
              <div class="space-y-2">
                <h3 class="text-md font-medium leading-5 text-gray-900 mb-4">
                  Control who has access...
                </h3>

                <div class="sm:grid sm:grid-cols-2">

                  <!-- Team/Org member access -->
                  <div class="flex flex-col border-b border-gray-50 sm:border-0 sm:border-r sm:pr-2">
                    <h4 class="text-sm font-medium leading-5 text-gray-900 mb-1.5 text-center">
                      Team Members
                    </h4>

                    <div>
                      <div class="flex space-x-2 justify-center">
                        <span>
                            <div class="inline-flex justify-center items-center h-8 w-8 bg-gray-50 text-orange-400 rounded-full">
                              <!-- YOU -->
                              <!-- TODO -->
                              Y
                            </div>
                        </span>
                      </div>
                    </div>
                  </div>

                  <!-- Recipients -->
                  <div class="flex flex-col border-t border-gray-50 sm:border-0 sm:border-l sm:pl-2">
                    <h4 class="text-sm font-medium leading-5 text-gray-900 mb-1.5 text-center">
                      Recipients
                    </h4>

                    <div>
                      <div class="flex space-x-2 justify-center">
                        <span>
                            <div class="inline-flex justify-center items-center h-8 w-8 bg-gray-50 text-orange-400 rounded-full">
                              <!-- YOU -->
                              <!-- TODO -->
                              Y
                            </div>
                        </span>
                      </div>
                    </div>
                  </div>

                </div>
              </div>


            </div>

            <!-- Another section -->
            <div class="space-y-4 pt-4 pb-6">
              <!-- Next Actions -->
              <div class="space-y-2">
                <h3 class="text-md font-medium leading-5 text-gray-900">
                  Define next steps/actions...
                </h3>


              </div>
            </div>

          </div>
        </div>
      </template>

      <template #footer>
        <span class="inline-flex rounded-md shadow-sm">
          <button @click.prevent="$emit('updatePressed')" type="submit" class="inline-flex justify-center py-2 px-4 border border-transparent text-sm leading-5 font-medium rounded-md text-white bg-orange-400 hover:bg-orange-300 focus:outline-none focus:border-orange-500 focus:shadow-outline-orange active:bg-orange-500 transition duration-150 ease-in-out">
            Update Proposal
          </button>
        </span>

      </template>

      <template #extra-close-button-handling="{ handleCloseButtonHit }">
        <!-- TODO fix shadow -->
        <button
          @click="handleExtraCloseButtonHit"
          class="absolute bottom-0 top-0 -ml-13 bg-white inline-block h-20 my-auto rounded-l-full w-7"
          style="margin-left: -1.65rem; box-shadow: -5px 3px 10px -2px rgba(0, 0, 0, 0.1), -5px 10px 10px -5px rgba(0, 0, 0, 0.04);">
          <svg class="h-5 w-5 ml-1 text-orange-400" fill="none" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" viewBox="0 0 24 24" stroke="currentColor"><path d="M9 5l7 7-7 7"></path></svg>
        </button>
      </template>

    </BaseSlideOver>
  </fragment>
</template>

<script>
import BaseSlideOver from '@/SlideOvers/BaseSlideOver'

export default {
  components: { BaseSlideOver },
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
