<template>
  <BaseVideoRecord
    ref="baseVideoRecord"
    :expand-animation="handleExpandAnimation"
    :collapse-animation="handleCollapseAnimation"
    :upload-video="handleUploadVideo">
    <template #template="{ currentState, context, sendEvent, currentStateDebugString }">

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

        <IconHeroiconsSpinner
          v-if="currentState.matches('collapsed.uploading')"
          class="h-8 w-8 text-red-400" />

        <IconHeroiconsMediumCog
          v-if="currentState.matches('collapsed.processing')"
          class="h-8 w-8 text-red-400 animate-spin" />

      </div>

      <!-- Expanded state... -->
      <portal to="proposal-view-portal">

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

            <ProposalIntroVideoExpanded
              ref="expandedCircle"
              v-if="currentState.matches('expanded') || currentState.matches('expanding') || currentState.matches('collapsing')"
              :current-state="currentState"
              :current-state-string="currentStateDebugString"
              :proposal="proposal"
              @from-playback-record-again="handleFromPlaybackRecordAgain"
              @cancel-from-recording="handleCancelFromRecording"
              @handleVideoRecordFinish="handleVideoRecordFinish"
              @from-confirm-record-again="handleFromConfirmRecordAgain"
              @recording-confirmed="handleRecordingConfirmed"
              @exit-from-webkit-safari="handleExitFromWebkitSafari"
              @exit-from-record-error="handleExitFromRecordError"
              @from-empty-start-record="handleFromEmptyStartRecord"
              @handle-video-record-device-error="handleVideoRecordDeviceError"
              />
          </template>
        </BaseModal>

      </portal>


    </template>
  </BaseVideoRecord>
</template>

<script>
import { BaseVideoRecord } from '@/Components/BaseVideoRecord'
import { illusory } from 'illusory'
import IconVideoMessage from '@/Components/IconVideoMessage'
import IconHeroiconsMediumCog from '@/Icons/IconHeroiconsMediumCog'
import IconHeroiconsSpinner from '@/Icons/IconHeroiconsSpinner'
import BaseModal from '@/Modals/BaseModal'
import Realtime from '@/services/Realtime'
import ProposalIntroVideoExpanded from '@/Components/ProposalIntroVideoExpanded'
import S3Upload from '@/models/S3Upload'
import { get, set } from 'lodash-es'

export default {
  components: {
    BaseVideoRecord, IconVideoMessage, IconHeroiconsMediumCog, IconHeroiconsSpinner, ProposalIntroVideoExpanded, BaseModal
  },
  props: {
    proposal: { type: Object }
  },
  data () {
    return {}
  },
  computed: {
    isExpanded: {
      get () {
        return this.$refs.baseVideoRecord.currentState.matches('expanded')
      },
      set (value) {
        if (!value) {
          this.$refs.baseVideoRecord.sendEvent('COLLAPSE')
        } else {
          this.$refs.baseVideoRecord.sendEvent('EXPAND')
        }
      }
    }
  },
  watch: {
    'proposal.has_intro_video': {
      immediate: true,
      async handler (value) {
        await this.$nextTick()
        this.$refs.baseVideoRecord.sendEvent({
          type: 'UPDATE_HAS_VIDEO',
          payload: value
        })
      }
    },
    'proposal.is_intro_video_processing': {
      // immediate: true,
      async handler (value) {
        await this.$nextTick()
        if (value) {
          this.$refs.baseVideoRecord.sendEvent('INITIALLY_IS_PROCESSING')
        } else {
          this.$refs.baseVideoRecord.sendEvent('INITIALLY_IS_NOT_PROCESSING')
        }
      }
    }
  },
  mounted () {
    const channel = `proposal.${this.proposal.uuid}.intro_video`
    Realtime.subscribe(channel, {
      'ProposalIntroVideoUpdated': this.handleRealtimeProposalIntroVideoUpdated
    })

    this.$once('hook:destroyed', () => {
      Realtime.unsubscribe(channel)
    })
  },
  methods: {
    handleExpand () {
      this.$refs.baseVideoRecord.sendEvent('EXPAND')
    },
    handleExpandedClick () {
      this.$refs.baseVideoRecord.sendEvent('COLLAPSE')
    },
    handleFromPlaybackRecordAgain () {
      this.$refs.baseVideoRecord.sendEvent('RECORD')
    },
    handleFromConfirmRecordAgain () {
      this.$refs.baseVideoRecord.sendEvent('RECORD_AGAIN')
    },
    handleCancelFromRecording () {
      this.$refs.baseVideoRecord.sendEvent('RECORDING_CANCEL')
    },
    handleRecordingConfirmed () {
      this.$refs.baseVideoRecord.sendEvent('CONFIRM_UPLOAD')
    },
    handleExitFromWebkitSafari () {
      this.$refs.baseVideoRecord.sendEvent('COLLAPSE')
    },
    handleExitFromRecordError () {
      this.$refs.baseVideoRecord.sendEvent('COLLAPSE')
    },
    handleFromEmptyStartRecord () {
      this.$refs.baseVideoRecord.sendEvent('RECORD')
    },
    handleVideoRecordDeviceError () {
      this.$refs.baseVideoRecord.sendEvent('RECORDING_ERROR')
    },
    handleVideoRecordFinish (player) {
      this.$refs.baseVideoRecord.sendEvent({
        type: 'RECORDED',
        payload: player.recordedData
      })
    },
    async handleExpandAnimation () {
      await this.$nextTick(async () => {
        await this.$nextTick()
        let { finished, cancel } = illusory(this.$refs.collapsedCircle, this.$refs.expandedCircle.$el)

        await finished
      })
    },
    async handleCollapseAnimation () {
      await this.$nextTick(async () => {
        await this.$nextTick()
        let { finished, cancel } = illusory(this.$refs.expandedCircle.$el, this.$refs.collapsedCircle)

        await finished
      })
    },
    async handleUploadVideo (videoFileBlob) {
      const s3Upload = S3Upload.make({ file: videoFileBlob })

      const s3UploadResponse = await s3Upload.store({
        progress: (onProgress) => {
          console.log(`on video upload progress: ${onProgress}`)
        }
      })

      const proposalVideoUpsertResponse = await this.proposal.videoIntroUpsert(s3UploadResponse.uuid)

      const introVideo = get(proposalVideoUpsertResponse, 'data')
      if (!introVideo) return
      this.proposal.fill({ intro_video: introVideo })
      this.$emit('update:proposal', this.proposal)
    },
    handleRealtimeProposalIntroVideoUpdated (event) {
      console.log('handleRealtimeProposalIntroVideoUpdated: ', event)
      const introVideo = get(event, 'intro_video')
      if (!introVideo) return
      this.proposal.fill({ intro_video: introVideo })
      this.$emit('update:proposal', this.proposal)
      this.$refs.baseVideoRecord.sendEvent('PROCESSING_COMPLETED')
    }
  }
}
</script>
