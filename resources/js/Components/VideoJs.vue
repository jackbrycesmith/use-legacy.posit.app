<template>
  <div>
    <video
      ref="video"
      playsinline
      class="video-js vjs-default-skin"
      :class="classes"
      :style="styles">
    </video>
  </div>
</template>

<script>
window.VIDEOJS_NO_BASE_THEME = true
window.VIDEOJS_NO_DYNAMIC_STYLE = true
import 'video.js/dist/video-js.css'
// import '@videojs/themes/dist/city/index.css';
import videojs from 'video.js'
// import 'videojs-record/dist/css/videojs.record.css'
// import 'webrtc-adapter'
// import RecordRTC from 'recordrtc'
// import Record from 'videojs-record/dist/videojs.record.js'

const RECORD_PLUGIN_EVENTS = [
  'deviceReady',
  'enumerateReady',
  'enumerateError',
  'startRecord',
  'finishRecord',
  'deviceError',
]

const DEFAULT_EVENTS = [
  'play',
  'pause',
  'error'
]

export default {
  props: {
    classes: { type: String, default: 'video-js vjs-default-skin' },
    playerClass: { type: String, default: 'vjs-posit' },
    styles: { type: String, default: '' },
    options: { type: Object, default: () => {} },
    ready: { type: Function, default: () => {} },
    events: { type: Array, default: () => [...DEFAULT_EVENTS] },
    debug: { type: Boolean, default: false },
    loadRecordPlugin: { type: Boolean, default: false },
    onDeviceReady: { type: Function },
    onEnumerateReady: { type: Function },
    onEnumerateError: { type: Function },
    onStartRecord: { type: Function },
    onFinishRecord: { type: Function },
    onError: { type: Function },
    onDeviceError: { type: Function },
  },
  data () {
    return {
      player: null
    }
  },
  async mounted () {
    if (this.loadRecordPlugin) {
      console.log('will import...')
      await import('videojs-record/dist/css/videojs.record.css')
      await import('recordrtc')
      await import('videojs-record/dist/videojs.record.js')
      await import('webrtc-adapter')
    }
    this.initPlayer()
  },
  methods: {
    initPlayer () {
      this.player = videojs(this.$refs.video, this.options, () => {
        if (this.debug) {
          const message = `Using video.js ${videojs.VERSION}`
          videojs.log(message)
        }

        this.setupVideoEventListeners()
        this.$emit('ready', this.player)
      })

      this.player.addClass(this.playerClass)
    },
    setupVideoEventListeners() {
      this.events.forEach(event => {
        this.player.on(event, () => {
          this.$emit(event, this.player)
        })
      })
    }
  },
  destroyed () {
    console.log('destroyed video js')
  },
  beforeDestroy () {
    console.log('before destroy video js')
    this.player?.dispose()
  }
}
</script>

<style>
.vjs-control-bar {
  margin-bottom: -2.5rem !important;
}

.vjs-posit .vjs-poster {
  background-size: cover;
  background-color: unset;
  position: unset;
}
</style>
