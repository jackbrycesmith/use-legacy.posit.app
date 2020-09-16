<template>
  <div
    class="relative bg-white h-72 w-72 sm:h-96 sm:w-96 rounded-full flex items-center justify-center shadow-md hover:bg-gray-50">

    <!-- Empty/First time -->
    <transition
      enter-active-class="ease-out duration-1000"
      enter-class="opacity-0"
      enter-to-class="opacity-100"
      leave-active-class="ease-in duration-1000"
      leave-class="opacity-100"
      leave-to-class="opacity-0">

      <div class="absolute inset-auto flex flex-col justify-evenly items-center h-full w-full rounded-full select-none" v-if="currentState.matches('expanded.empty')" style="transition-delay: .4s">

        <h3 class="xs:text-lg text-xl font-medium text-center">
          <PositLogoWords class="h-8 w-36 m-auto" />
          <span class="text-orange-400">Record your intro video</span>
        </h3>

        <span v-if="!isErrorCannotRecord" class="xs:text-4xl text-5xl inline-block animate-wave">👋</span>

        <transition
          enter-active-class="ease-out duration-1000"
          enter-class="opacity-0"
          enter-to-class="opacity-100"
          leave-active-class="ease-in duration-1000"
          leave-class="opacity-100"
          leave-to-class="opacity-0">
          <span v-if="isErrorCannotRecord" v-html="cannotRecordVideoErrorMessage" class="text-sm text-gray-500 px-8 text-center"/>
        </transition>

        <span v-if="!isErrorCannotRecord" class="inline-flex rounded-md shadow-sm">
          <button @click="handleStartNowFromEmpty" type="button" class="inline-flex items-center px-4 py-2 border border-transparent text-base leading-6 font-medium rounded-md text-white bg-orange-400 hover:bg-orange-300 focus:outline-none focus:border-orange-500 focus:shadow-outline-orange active:bg-orange-500 transition duration-150 ease-in-out">
            Start Now »
          </button>
        </span>

        <span v-if="isErrorCannotRecord" class="inline-flex rounded-md shadow-sm">
          <button @click="$emit('exit-from-webkit-safari')" type="button" class="inline-flex items-center px-4 py-2 border border-transparent text-base leading-6 font-medium rounded-md text-white bg-orange-400 hover:bg-orange-300 focus:outline-none focus:border-orange-500 focus:shadow-outline-orange active:bg-orange-500 transition duration-150 ease-in-out">
            Okay
          </button>
        </span>

      </div>
    </transition>

    <!-- TODO playback -->
    <template v-if="currentState.matches('expanded.playback')" class="relative w-full h-full">
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

      <span class="inline-flex rounded-md shadow-sm absolute top-0 left-auto right-auto -mt-12">
        <button @click="$emit('from-playback-record-again')" type="button" class="inline-flex items-center px-4 py-2 border border-transparent text-sm leading-5 font-medium rounded-md text-gray-700 bg-white hover:text-gray-500 focus:outline-none focus:border-blue-300 focus:shadow-outline-blue active:text-gray-800 active:bg-gray-50 transition ease-in-out duration-150">
          <svg class="-ml-1 mr-2 h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
            <path d="M2 6a2 2 0 012-2h6a2 2 0 012 2v8a2 2 0 01-2 2H4a2 2 0 01-2-2V6zM14.553 7.106A1 1 0 0014 8v4a1 1 0 00.553.894l2 1A1 1 0 0018 13V7a1 1 0 00-1.447-.894l-2 1z" />
          </svg>
          Record again
        </button>
      </span>
    </template>

    <!-- Record Error -->
    <transition
      enter-active-class="ease-out duration-1000"
      enter-class="opacity-0"
      enter-to-class="opacity-100"
      leave-active-class="ease-in duration-1000"
      leave-class="opacity-100"
      leave-to-class="opacity-0">

      <div class="absolute inset-auto flex flex-col justify-evenly items-center h-full w-full rounded-full select-none" v-if="currentState.matches('expanded.recordError')" style="transition-delay: .2s">

        <h3 class="xs:text-lg text-xl font-medium text-center">
          <PositLogoWords class="h-8 w-36 m-auto" />
          <span class="text-orange-400">Record your intro video</span>
        </h3>

        <span v-html="cameraPermissionDeniedMessage" class="text-sm text-gray-500 px-8 text-center"/>

        <span class="inline-flex rounded-md shadow-sm">
          <button @click="$emit('exit-from-record-error')" type="button" class="inline-flex items-center px-4 py-2 border border-transparent text-base leading-6 font-medium rounded-md text-white bg-orange-400 hover:bg-orange-300 focus:outline-none focus:border-orange-500 focus:shadow-outline-orange active:bg-orange-500 transition duration-150 ease-in-out">
            Okay
          </button>
        </span>

      </div>
    </transition>

    <!-- TODO recording -->
    <template v-if="currentState.matches('expanded.recording')" class="relative w-full h-full">
      <VideoJs
        v-if="currentState.matches('expanded.recording')"
        key="def"
        class="w-full h-full"
        classes="w-full h-full"
        style="object-fit: cover; border-radius: 9999px;"
        styles="object-fit: cover; border-radius: 9999px; height: 100%; width: 100%;"
        :options="videoJsRecordOptions"
        :load-record-plugin="true"
        :events="['deviceReady', 'enumerateReady', 'enumerateError', 'startRecord', 'finishRecord', 'deviceError']"
        @deviceError="handleVideoRecordDeviceError"
        @ready="handleVideoRecordReady"
        @finishRecord="handleVideoRecordFinish"
      />

      <span v-if="currentState.context.hasVideo" class="inline-flex rounded-md shadow-sm absolute top-0 left-0 -mt-12">
        <button @click="$emit('cancel-from-recording')" type="button" class="inline-flex items-center px-4 py-2 border border-transparent text-sm leading-5 font-medium rounded-md text-gray-700 bg-white hover:text-gray-500 focus:outline-none focus:border-blue-300 focus:shadow-outline-blue active:text-gray-800 active:bg-gray-50 transition ease-in-out duration-150">
          <svg class="-ml-1 mr-2 h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
            <path fill-rule="evenodd" d="M12.707 5.293a1 1 0 010 1.414L9.414 10l3.293 3.293a1 1 0 01-1.414 1.414l-4-4a1 1 0 010-1.414l4-4a1 1 0 011.414 0z" clip-rule="evenodd" />
          </svg>
          Previous recording
        </button>
      </span>
    </template>

    <!-- START recordedConfirmUpload || uploading || uploadingFailed -->
    <template
      v-if="currentState.matches('expanded.recordedConfirmUpload') || currentState.matches('expanded.uploading') || currentState.matches('expanded.uploadingFailed') || currentState.matches('expanded.processing')"
      class="relative w-full h-full">
      <VideoJs
        v-if="currentState.matches('expanded.recordedConfirmUpload') || currentState.matches('expanded.uploading') || currentState.matches('expanded.uploadingFailed') || currentState.matches('expanded.processing')"
        ref="videoRecordConfirm"
        key="ghi"
        class="w-full h-full"
        classes="w-full h-full"
        style="object-fit: cover; border-radius: 9999px;"
        styles="object-fit: cover; border-radius: 9999px; height: 100%; width: 100%;"
        :options="videoJsPlaybackOptions"
        :load-record-plugin="false"
        @ready="handleVideoConfirmUploadReady"
      />

      <!-- Confirm/Retake video buttons -->
      <template v-if="currentState.matches('expanded.recordedConfirmUpload') || currentState.matches('expanded.uploadingFailed')">
        <span class="inline-flex rounded-md shadow-sm absolute top-0 left-0 -mt-12">
          <button @click="$emit('from-confirm-record-again')" type="button" class="inline-flex items-center px-4 py-2 border border-transparent text-sm leading-5 font-medium rounded-md text-gray-700 bg-white hover:text-gray-500 focus:outline-none focus:border-blue-300 focus:shadow-outline-blue active:text-gray-800 active:bg-gray-50 transition ease-in-out duration-150">
            <IconHeroiconsSmallX class="-ml-1 mr-2 h-5 w-5 text-red-400" />
            Retake
          </button>
        </span>

        <span class="inline-flex rounded-md shadow-sm absolute top-0 right-0 -mt-12">
          <button @click="$emit('recording-confirmed')" type="button" class="inline-flex items-center px-4 py-2 border border-transparent text-sm leading-5 font-medium rounded-md text-gray-700 bg-white hover:text-gray-500 focus:outline-none focus:border-blue-300 focus:shadow-outline-blue active:text-gray-800 active:bg-gray-50 transition ease-in-out duration-150">
            Use
            <IconHeroiconsSmallCheck class="ml-2 -mr-1 h-5 w-5 text-green-400" />
          </button>
        </span>
      </template>

      <!-- Uploading visual context -->
      <template v-if="currentState.matches('expanded.uploading')">
        <IconHeroiconsSpinner
          class="h-16 w-16 text-red-400 absolute inset-auto" />

        <span class="inline-flex items-center px-3 py-0.5 rounded-full text-sm font-medium leading-5 bg-yellow-100 text-yellow-800 absolute top-0 left-auto right-auto -mt-12">
          <svg class="-ml-1 mr-1.5 h-2 w-2 text-indigo-400" fill="currentColor" viewBox="0 0 8 8">
            <circle cx="4" cy="4" r="3" />
          </svg>
          Uploading&hellip;
        </span>
      </template>

      <!-- Uploading failed visual context -->
      <template v-if="currentState.matches('expanded.uploadingFailed')">
        <!-- TODO better to show that the upload failed -->
        <span class="inline-flex items-center px-3 py-0.5 rounded-full text-sm font-medium leading-5 bg-yellow-100 text-yellow-800 absolute top-0 left-auto right-auto -mt-12">
          <svg class="-ml-1 mr-1.5 h-2 w-2 text-indigo-400" fill="currentColor" viewBox="0 0 8 8">
            <circle cx="4" cy="4" r="3" />
          </svg>
          Upload Failed
        </span>
      </template>

      <!-- Processing visual context -->
      <template v-if="currentState.matches('expanded.processing')">
        <IconHeroiconsMediumCog
          class="h-16 w-16 text-red-400 animate-spin absolute inset-auto" />

        <span class="inline-flex items-center px-3 py-0.5 rounded-full text-sm font-medium leading-5 bg-yellow-100 text-yellow-800 absolute top-0 left-auto right-auto -mt-12">
          <svg class="-ml-1 mr-1.5 h-2 w-2 text-indigo-400" fill="currentColor" viewBox="0 0 8 8">
            <circle cx="4" cy="4" r="3" />
          </svg>
          Processing&hellip;
        </span>
      </template>
    </template>
    <!-- END recordedConfirmUpload || uploading || uploadingFailed -->

  </div>
</template>

<script>
import VideoJs from '@/Components/VideoJs'
import IconHeroiconsSmallCheck from '@/Icons/IconHeroiconsSmallCheck'
import IconHeroiconsSmallX from '@/Icons/IconHeroiconsSmallX'
import PositLogoWords from '@/Components/PositLogoWords'
import IconHeroiconsSpinner from '@/Icons/IconHeroiconsSpinner'
import IconHeroiconsMediumCog from '@/Icons/IconHeroiconsMediumCog'
import { isWebkitSafari } from '@/utils/is'
import { isNil } from 'lodash-es'

export default {
  components: { VideoJs, IconHeroiconsSmallCheck, IconHeroiconsSmallX, IconHeroiconsSpinner, IconHeroiconsMediumCog, PositLogoWords },
  props: {
    proposal: { type: Object },
    currentState: { type: Object },
    currentStateString: { type: String },
  },
  data () {
    return {
      isWebkitSafari: false,
      isCameraMicrophonePermissionDenied: false
    }
  },
  computed: {
    isErrorCannotRecord () {
      return this.isWebkitSafari || this.isCameraMicrophonePermissionDenied
    },
    cannotRecordVideoErrorMessage () {
      if (this.isWebkitSafari) {
        return `
          The recording feature is <span class="underline">currently unavailable on iOS & Safari</span> due to browser restrictions.
            <br><br>
            Please use another browser/device if possible whilst we look for workarounds.
        `
      }

      if (this.isCameraMicrophonePermissionDenied) {
        return this.cameraPermissionDeniedMessage
      }
    },
    cameraPermissionDeniedMessage () {
      return `
          Camera/mic permission has been denied.<br><br>
          Please enable access & try again.
        `
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
    videoJsRecordOptions () {
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
          plugins: {
              // configure videojs-record plugin
              record: {
                  pip: false,
                  audio: true,
                  maxLength: 30,
                  debug: true,
                  videoMimeType: 'video/webm;codecs=vp8',
                  video: {
                    // video media constraints: set resolution of camera
                    width: 380,
                    height: 300
                  },
                  // dimensions of captured video frames
                  frameWidth: 380,
                  frameHeight: 300
              }
          }
      }
    }
  },
  watch: {
    'currentStateString': {
      immediate: true,
      deep: true,
      handler (value) {
        console.log(`currentStateString changed: ${value}`)
      }
    },
    'currentState': {
      immediate: true,
      // deep: true,
      handler (value) {

      }
    }
  },
  methods: {
    async handleStartNowFromEmpty () {
      if (isWebkitSafari()) {
        // TODO fire some analytics event to track errors e.g. if iOS/Safari
        this.isWebkitSafari = true
        return
      }

      try {
        // TODO should probs move this out to utils & also check microphone too
        const result = await navigator.permissions.query({ name: 'camera' })

        if (result.state === 'denied') {
          this.isCameraMicrophonePermissionDenied = true
          return
        }
      } catch (e) {}

      this.$emit('from-empty-start-record')
    },
    async handleVideoPlaybackReady (player) {
      await this.$nextTick()

      // player.on('play', () => {
      //   console.log('on play...')
      // })

      // player.on('pause', () => {
      //   console.log('on pause...')
      // })

      console.log('handleVideoPlaybackReady: ', this.proposal.intro_video)
      // Load existing video
      if (this.proposal.has_intro_video) {
        if (this.proposal.intro_video.has_poster) {
          player.poster(this.proposal.intro_video.poster_url)
        } else {
          player.poster('') // Reset
        }

        console.log('setting src...: ', this.proposal.intro_video.video_js_src_data)
        player.src(this.proposal.intro_video.video_js_src_data)
        player.play()
      }
    },
    async handleVideoConfirmUploadReady (player) {
      await this.$nextTick()
      if (isNil(this.currentState.context.recordedFile)) return

      const recordedSrc = URL.createObjectURL(this.currentState.context.recordedFile)

      // For some reason safari(expiremental) still borks this; see https://github.com/collab-project/videojs-record/issues/479
      player.src({ src: recordedSrc, type: this.currentState.context.recordedFile.type })
      player.play()
    },
    handleVideoRecordFinish (player) {
      this.$emit('handleVideoRecordFinish', player)
    },
    handleVideoRecordReady (player) {
      player.record().getDevice()
    },
    handleVideoRecordDeviceError (player) {
      this.$emit('handle-video-record-device-error')
    },
  }
}
</script>
