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
        'collapseAnimation': this.collapseAnimation
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
    currentStateDebugString () {
      return this.currentState.toStrings().join(' ')
    }
  },
  methods: {
    sendEvent(event) {
      this.machineService.send(event)
    },
    test () {
      console.log('testtyy')
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
