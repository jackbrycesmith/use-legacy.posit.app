<template>
  <fragment>
    <slot name="template" v-bind="{ currentState, context, sendEvent, currentStateDebugString }">

    </slot>
  </fragment>
</template>

<script>
import { interpret, assign } from 'xstate'
import { videoRecordMachine } from '@/Components/BaseVideoRecord'

export default {
  props: {
    expandAnimation: { type: Function, default: async () => {} },
    collapseAnimation: { type: Function, default: async () => {} },
    uploadVideo: { type: Function, default: async (videoFileBlob) => {
      console.log('uploadVideo...')
    }},

    // Xstate Guards
    isCollapsedEmpty: { type: Function, default: (context, event) => { return true } },
    isCollapsedExisting: { type: Function, default: (context, event) => { return false } },
    isCollapsedUploading: { type: Function, default: (context, event) => { return false } },
    isCollapsedUploadingFailed: { type: Function, default: (context, event) => { return false } },
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
    const machine = videoRecordMachine.withConfig({
      services: {
        'expandAnimation': this.expandAnimation,
        'collapseAnimation': this.collapseAnimation,
      },
      actions: {
        setUploadingContext: assign((context, event) => {
          context.isUploading = true
          context.uploadFailed = false
        }),
        setUploadCompletedContext: assign((context, event) => {
          context.isUploading = false
        }),

        setRecordAgainContext: assign((context, event) => {
          context.recordedFile = null
        }),
        setUploadFailedContext: assign((context, event) => {
          context.isUploading = false
          context.uploadFailed = true
        }),
        updateHasVideo: assign((context, event) => {
          context.hasVideo = !!event.payload
        }),
        updateRecordedFile: assign((context, event) => {
          context.recordedFile = event.payload
        }),
        uploadVideo: assign(async (context, event) => {
          try {
            await this.uploadVideo(context.recordedFile)
            this.sendEvent('UPLOAD_SUCCESS')
            console.log('uploadVideo finish....')
          } catch (e) {
            this.sendEvent('UPLOAD_FAILED')
            console.log('upload failed....')
          }
        }),
        setIsProcessingContext: assign((context, event) => {
          context.isProcessing = true
        }),
        setIsNotProcessingContext: assign((context, event) => {
          context.isProcessing = false
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
    currentStateDebugString () {
      return this.currentState.toStrings().join(' ')
    }
  },
  methods: {
    sendEvent(event) {
      this.machineService.send(event)
    },
    async expandAnimation__ () {
      console.log('expandAnimation__')
    },
    async collapseAnimation__ () {
      console.log('collapseAnimation__')
    },
  }
}
</script>
