<template>
  <fragment>

    <!-- Collapsed state... -->
    <div
      ref="collapsedCircle"
      v-if="currentState.matches('collapsed') || currentState.matches('expanding') || currentState.matches('collapsing')"
      class="bg-white h-32 w-32 rounded-full flex items-center justify-center hover:bg-gray-50 cursor-pointer absolute bottom-0 left-auto right-auto -mb-16 shadow-md"
      @click="handleExpand">

      <!-- Example empty icon for new video... -->
      <IconVideoMessage v-if="currentState.matches('collapsed.empty')" class="h-8 w-8 text-red-400" />

      <!-- TODO should probably have this frosted effect on the expanded thing, when collapsing/on collapse too to stop the brief flash of bg-white -->
      <template v-if="currentState.matches('collapsed.existing') || currentState.matches('expanding') || currentState.matches('collapsing')">
        <div class="relative w-full h-full rounded-full flex items-center justify-center">
          <!-- Poster... -->
          <img
            v-if="proposal.has_intro_video && proposal.intro_video.has_poster"
            :src="proposal.intro_video.poster_url"
            style="width: 96%; height: 96%;"
            class="absolute inset-auto rounded-full"/>

          <!-- Frosted glass effect -->
          <div
            style="background-color: rgba(255,255,255,0.5); backdrop-filter: blur(2px);"
            class="absolute inset-auto rounded-full w-full h-full border-4 border-white"/>

          <!-- Example play button for existing video... -->
          <svg v-if="currentState.matches('collapsed.existing')"  class="h-8 w-8 text-red-400" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" aria-hidden="true" focusable="false" role="img"  width="1em" height="1em" preserveAspectRatio="xMidYMid meet" viewBox="0 0 512 512" style="transform: rotate(360deg);"><path d="M256 48C141.1 48 48 141.1 48 256s93.1 208 208 208 208-93.1 208-208S370.9 48 256 48zm83.8 211.9l-137.2 83c-2.9 1.8-6.7-.4-6.7-3.9V173c0-3.5 3.7-5.7 6.7-3.9l137.2 83c2.9 1.7 2.9 6.1 0 7.8z" fill="currentColor"></path></svg>
        </div>
      </template>

    </div>

    <!-- Expanded state... -->
    <portal to="posit-view-portal">

      <BaseModal
        ref="modal"
        :is-visible="currentState.matches('expanded') || currentState.matches('expanding')"
        :on-background-hit="handleExpandedClick"
        :on-escape-key-hit="handleExpandedClick"
        :root-visible-hide-delay="300"
        :show-default-modal-style="false"
        modal-root-class="fixed bottom-0 inset-x-0 px-4 pb-4 inset-0 flex items-center justify-center"
      >
        <template #alternative-modal>

          <div
            ref="expandedCircle"
            class="relative bg-white h-72 w-72 sm:h-96 sm:w-96 rounded-full flex items-center justify-center shadow-md hover:bg-gray-50">

            <template
              v-if="currentState.matches('expanded.playback')"
              class="relative w-full h-full">
              <VideoJs
                v-if="currentState.matches('expanded.playback')"
                key="abc"
                ref="videoPlayback"
                class="w-full h-full"
                style="object-fit: cover; border-radius: 9999px;"
                classes="w-full h-full"
                styles="object-fit: cover; border-radius: 9999px; height: 100%; width: 100%;"
                :options="videoJsPlaybackOptions"
                @ready="handleVideoPlaybackReady"
                />
            </template>

          </div>


        </template>
      </BaseModal>

    </portal>

  </fragment>
</template>

<script>
import { interpret, assign } from 'xstate'
import { videoPlaybackOnlyMachine } from '@/machines/videoPlaybackOnlyMachine'
import { BaseVideoRecord } from '@/Components/BaseVideoRecord'
import { illusory } from 'illusory'
import IconVideoMessage from '@/Components/IconVideoMessage'
import IconHeroiconsMediumCog from '@/Icons/IconHeroiconsMediumCog'
import IconHeroiconsSpinner from '@/Icons/IconHeroiconsSpinner'
import BaseModal from '@/Modals/BaseModal'
import PositIntroVideoExpanded from '@/Components/PositIntroVideoExpanded'
import VideoJs from '@/Components/VideoJs'
import { get, set } from 'lodash-es'

export default {
  components: {
    BaseVideoRecord,
    IconVideoMessage,
    IconHeroiconsMediumCog,
    IconHeroiconsSpinner,
    PositIntroVideoExpanded,
    BaseModal,
    VideoJs
  },
  props: {
    proposal: { type: Object }
  },
  created () {
    this.machineService
      .onTransition(state => {
        // Update the currentState state component data property with the next state
        this.currentState = state
        // Update the context component data property with the updated context
        this.context = state.context
      })
      .start()
  },
  data () {
    const machine = videoPlaybackOnlyMachine.withConfig({
      services: {
        'expandAnimation': this.expandAnimation,
        'collapseAnimation': this.collapseAnimation,
      },
      actions: {
        updateHasVideo: assign((context, event) => {
          context.hasVideo = !!event.payload
        }),
      }
    })

    return {
      machineService: interpret(machine, { devTools: true }),
      currentState: machine.initialState,
      context: machine.context
    }
  },
  computed: {
    isExpanded: {
      get () {
        return this.currentState.matches('expanded')
      },
      set (value) {
        if (!value) {
          this.machineService.send('COLLAPSE')
        } else {
          this.machineService.send('EXPAND')
        }
      }
    },
    videoJsPlaybackOptions () {
      return {
          controls: true,
          autoplay: false,
          fluid: false,
          loop: false,
          width: 380,
          height: 300,
          bigPlayButton: false,
          controlBar: {
              volumePanel: false,
              // pipToggle: false,
              fullscreenToggle: false,
              pictureInPictureToggle: false
          },
          userActions: {
            doubleClick: false
          },
          plugins: {}
      }
    },
  },
  watch: {
    'proposal.has_intro_video': {
      immediate: true,
      async handler (value) {
        await this.$nextTick()
        this.machineService.send({
          type: 'UPDATE_HAS_VIDEO',
          payload: value
        })
      }
    },
  },
  mounted () {

  },
  methods: {
    handleExpand () {
      this.machineService.send('EXPAND')
    },
    handleExpandedClick () {
      this.machineService.send('COLLAPSE')
    },
    async expandAnimation () {
      await this.$nextTick(async () => {
        await this.$nextTick()
        let { finished, cancel } = illusory(this.$refs.collapsedCircle, this.$refs.expandedCircle)

        await finished
      })
    },
    async collapseAnimation () {
      await this.$nextTick(async () => {
        await this.$nextTick()
        let { finished, cancel } = illusory(this.$refs.expandedCircle, this.$refs.collapsedCircle)

        await finished
      })
    },
    async handleVideoPlaybackReady (player) {
      await this.$nextTick()

      // player.on('play', () => {
      //   console.log('on play...')
      // })

      // player.on('pause', () => {
      //   console.log('on pause...')
      // })

      // Load existing video
      if (this.proposal.has_intro_video) {
        if (this.proposal.intro_video.has_poster) {
          player.poster(this.proposal.intro_video.poster_url)
        } else {
          player.poster('') // Reset
        }

        player.src(this.proposal.intro_video.video_js_src_data)
        player.play()
      }
    },
  }
}
</script>
