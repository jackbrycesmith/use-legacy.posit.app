<template>
  <div
    class="relative bg-white h-72 w-72 sm:h-96 sm:w-96 rounded-full flex items-center justify-center hover:bg-gray-50 cursor-pointer shadow-md">

    <!-- TODO playback -->
    <template v-if="currentState.matches('expanded.playback')">
      <video
        ref="video"
        playsinline
        class="video-js vjs-default-skin w-full h-full rounded-full"
        style="object-fit: cover; border-radius: 9999px;"
        ></video>

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
    <template v-if="currentState.matches('expanded.recording')">
      <video
        ref="videoRecord"
        playsinline
        class="video-js vjs-default-skin w-full h-full rounded-full"
        style="object-fit: cover; border-radius: 9999px;"
        ></video>
    </template>

    <!-- TODO recordedConfirmUpload -->


  </div>
</template>

<script>
import 'video.js/dist/video-js.css'
import 'videojs-record/dist/css/videojs.record.css'
import 'webrtc-adapter'
import videojs from 'video.js'
import RecordRTC from 'recordrtc'
import Record from 'videojs-record/dist/videojs.record.js'

export default {
  props: {
    proposal: { type: Object },
    currentState: { type: Object },
    currentStateString: { type: String },
  },
  data () {
    return {
      player: null,

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
        if (this.currentState.matches('expanded.playback')) {
          this.handleExpandedPlayback()
        }

        if (this.currentState.matches('expanded.recording')) {
          this.handleExpandedRecording()
        }
      }
    }
  },
  beforeDestroy () {
    this.player?.dispose()
  },
  methods: {
    async handleExpandedPlayback () {
      // TODO setup videojs for playback existing...
      // this.player?.dispose()
      await this.$nextTick()
      const videoJsPlaybackOptions = {
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

      this.player = videojs(this.$refs.video, videoJsPlaybackOptions, () => {
        // print version information at startup
        var msg = 'Using video.js ' + videojs.VERSION;
        videojs.log(msg);
      });

      // Load existing video
      if (this.proposal.has_intro_video) {
        this.player.src(this.proposal.intro_video.video_js_src_data)
        this.player.play()
      }

    },
    async handleExpandedRecording () {
      // TODO setup videojs for playback existing...
      this.player?.dispose()
      await this.$nextTick()
      const videoJsPlaybackOptions = {
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

      this.player = videojs(this.$refs.videoRecord, videoJsPlaybackOptions, () => {
        // print version information at startup
        var msg = 'Using video.js ' + videojs.VERSION +
            ' with videojs-record ' + videojs.getPluginVersion('record') +
            ' and recordrtc ' + RecordRTC.version;
        videojs.log(msg);
      });

      // device is ready
      this.player.on('deviceReady', () => {
        console.log('device is ready!');
        // enumerate devices once
        this.player.record().enumerateDevices()
      })

      // enumarate is ready
      this.player.on('enumerateReady', () => {
        console.log('enumerateReady is ready!')
        const devices = player.record().devices
        console.log('enumerateReady devices: ', devices)
      })

      this.player.on('enumerateError', function() {
        console.warn('enumerate error: ', this.player.enumerateErrorCode)
      })

      // user clicked the record button and started recording
      this.player.on('startRecord', () => {
        console.log('started recording!');
      })

      // user completed recording and stream is available
      this.player.on('finishRecord', () => {
      // the blob object contains the recorded data that
      // can be downloaded by the user, stored on server etc.
        console.log('finished recording: ', this.player.recordedData);
        this.handleVideoUpload(this.player.recordedData)
      })

      // error handling
      this.player.on('error', (element, error) => {
        console.warn(error);
      })

      this.player.on('deviceError', () => {
        console.error('device error:', this.player.deviceErrorCode);
      })
    }
  }
}
</script>
