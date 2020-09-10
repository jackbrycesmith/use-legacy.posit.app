<template>
  <div
    class="relative bg-white h-72 w-72 sm:h-96 sm:w-96 rounded-full flex items-center justify-center hover:bg-gray-50 cursor-pointer shadow-md">

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

    <!-- TODO recording -->
    <template v-if="currentState.matches('expanded.recording')" class="relative w-full h-full">
      <VideoJs
        v-if="currentState.matches('expanded.recording')"
        ref="videoRecord"
        key="def"
        class="w-full h-full"
        classes="w-full h-full"
        style="object-fit: cover; border-radius: 9999px;"
        styles="object-fit: cover; border-radius: 9999px; height: 100%; width: 100%;"
        :options="videoJsRecordOptions"
        :load-record-plugin="true"
        :events="['finishRecord']"
        @ready="handleVideoRecordReady"
        @finishRecord="handleVideoRecordFinish"
      />

      <span v-if="currentState.context.hasVideo" class="inline-flex rounded-md shadow-sm absolute top-0 left-auto right-auto -mt-12">
        <button @click="$emit('cancel-from-recording')" type="button" class="inline-flex items-center px-4 py-2 border border-transparent text-sm leading-5 font-medium rounded-md text-gray-700 bg-white hover:text-gray-500 focus:outline-none focus:border-blue-300 focus:shadow-outline-blue active:text-gray-800 active:bg-gray-50 transition ease-in-out duration-150">
          <svg class="-ml-1 mr-2 h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
            <path fill-rule="evenodd" d="M12.707 5.293a1 1 0 010 1.414L9.414 10l3.293 3.293a1 1 0 01-1.414 1.414l-4-4a1 1 0 010-1.414l4-4a1 1 0 011.414 0z" clip-rule="evenodd" />
          </svg>
          Previous recording
        </button>
      </span>
    </template>

    <!-- TODO recordedConfirmUpload -->
    <template v-if="currentState.matches('expanded.recordedConfirmUpload')" class="relative w-full h-full">
      <VideoJs
        v-if="currentState.matches('expanded.recordedConfirmUpload')"
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

      <span class="inline-flex rounded-md shadow-sm absolute top-0 left-0 -mt-12">
        <button @click="$emit('from-confirm-record-again')" type="button" class="inline-flex items-center px-4 py-2 border border-transparent text-sm leading-5 font-medium rounded-md text-gray-700 bg-white hover:text-gray-500 focus:outline-none focus:border-blue-300 focus:shadow-outline-blue active:text-gray-800 active:bg-gray-50 transition ease-in-out duration-150">
          <svg class="-ml-1 mr-2 h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
            <path fill-rule="evenodd" d="M12.707 5.293a1 1 0 010 1.414L9.414 10l3.293 3.293a1 1 0 01-1.414 1.414l-4-4a1 1 0 010-1.414l4-4a1 1 0 011.414 0z" clip-rule="evenodd" />
          </svg>
          Record again
        </button>
      </span>

      <span class="inline-flex rounded-md shadow-sm absolute top-0 right-0 -mt-12">
        <button @click="$emit('recording-confirmed')" type="button" class="inline-flex items-center px-4 py-2 border border-transparent text-sm leading-5 font-medium rounded-md text-gray-700 bg-white hover:text-gray-500 focus:outline-none focus:border-blue-300 focus:shadow-outline-blue active:text-gray-800 active:bg-gray-50 transition ease-in-out duration-150">
          <svg class="-ml-1 mr-2 h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
            <path fill-rule="evenodd" d="M12.707 5.293a1 1 0 010 1.414L9.414 10l3.293 3.293a1 1 0 01-1.414 1.414l-4-4a1 1 0 010-1.414l4-4a1 1 0 011.414 0z" clip-rule="evenodd" />
          </svg>
          I'm happy with this intro
        </button>
      </span>
    </template>


  </div>
</template>

<script>
import VideoJs from '@/Components/VideoJs'
// import 'video.js/dist/video-js.css'
// import 'videojs-record/dist/css/videojs.record.css'
// import 'webrtc-adapter'

export default {
  components: { VideoJs },
  props: {
    proposal: { type: Object },
    currentState: { type: Object },
    currentStateString: { type: String },
  },
  data () {
    return {
    }
  },
  computed: {
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
        // if (this.currentState.matches('expanded.playback')) {
        //   this.handleExpandedPlayback()
        // }

        // if (this.currentState.matches('expanded.recording')) {
        //   this.handleExpandedRecording()
        // }
      }
    }
  },
  methods: {
    async handleVideoPlaybackReady (player) {
      await this.$nextTick()

      player.on('play', () => {
        console.log('on play...')
      })

      player.on('pause', () => {
        console.log('on pause...')
      })
      console.log('player... ', player)

      // Load existing video
      if (this.proposal.has_intro_video) {
        player.src(this.proposal.intro_video.video_js_src_data)
        player.play()
      }
    },
    async handleVideoConfirmUploadReady (player) {
      await this.$nextTick()

      const recordedSrc = URL.createObjectURL(this.currentState.context.recordedFile)
      console.log(recordedSrc)

      // For some reason safari(expiremental) still borks this; see https://github.com/collab-project/videojs-record/issues/479
      player.src({ src: recordedSrc, type: this.currentState.context.recordedFile.type })
      player.play()
    },
    handleVideoRecordFinish (player) {
      console.log('handleVideoRecordFinish...')
      this.$emit('handleVideoRecordFinish', player)
    },
    async handleVideoRecordReady () {

    },
    async handleExpandedRecording () {

      // this.playerRecord = videojs(this.$refs.videoRecord, videoJsPlaybackOptions, () => {
      //   // print version information at startup
      //   var msg = 'Using video.js ' + videojs.VERSION +
      //       ' with videojs-record ' + videojs.getPluginVersion('record') +
      //       ' and recordrtc ' + RecordRTC.version;
      //   videojs.log(msg);
      // });

      // // device is ready
      // this.playerRecord.on('deviceReady', () => {
      //   console.log('device is ready!');
      //   // enumerate devices once
      //   this.playerRecord.record().enumerateDevices()
      // })

      // // enumarate is ready
      // this.playerRecord.on('enumerateReady', () => {
      //   console.log('enumerateReady is ready!')
      //   const devices = this.playerRecord.record().devices
      //   console.log('enumerateReady devices: ', devices)
      // })

      // this.playerRecord.on('enumerateError', function() {
      //   console.warn('enumerate error: ', this.playerRecord.enumerateErrorCode)
      // })

      // // user clicked the record button and started recording
      // this.playerRecord.on('startRecord', () => {
      //   console.log('started recording!');
      // })

      // // user completed recording and stream is available
      // this.playerRecord.on('finishRecord', () => {
      // // the blob object contains the recorded data that
      // // can be downloaded by the user, stored on server etc.
      //   console.log('finished recording: ', this.playerRecord.recordedData);
      //   // this.handleVideoUpload(this.playerRecord.recordedData)
      // })

      // // error handling
      // this.playerRecord.on('error', (element, error) => {
      //   console.warn(error);
      // })

      // this.playerRecord.on('deviceError', () => {
      //   console.error('device error:', this.playerRecord.deviceErrorCode);
      // })
    }
  }
}
</script>
