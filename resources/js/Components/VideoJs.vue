<template>
  <div>
    <video
      ref="video"
      playsinline
      :class="classes"
      :styles="styles">
    </video>
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
    classes: { type: String, default: 'video-js vjs-default-skin' },
    styles: { type: String, default: '' },
    options: { type: Object, default: () => {} },
    ready: { type: Function, default: () => {} },

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
  mounted () {
    this.player = videojs(this.$refs.video, this.options, this.ready)
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
