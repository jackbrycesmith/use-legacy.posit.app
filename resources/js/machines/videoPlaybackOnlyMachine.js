import { Machine } from 'xstate'

export const videoPlaybackOnlyMachine = Machine({
  id: 'videoPlaybackOnlyMachine',
  context: {
    hasVideo: false,
  },
  initial: 'collapsed',
  states: {
    // Collapsed states
    collapsed: {
      initial: 'entering',
      on: {
        EXPAND: 'expanding',
        UPDATE_HAS_VIDEO: {
          target: ['.entering'],
          internal: true,
          actions: ['updateHasVideo'],
        },
      },
      states: {
        entering: {
          always: [
            {
              target: 'empty', cond: 'isCollapsedEmpty'
            },
            {
              target: 'existing', cond: 'isCollapsedExisting'
            },
          ]
        },
        empty: {

        },
        existing: {

        },
      }
    },
    expanding: {
      invoke: {
        src: 'expandAnimation',
        onDone: 'expanded'
      }
    },
    collapsing: {
      invoke: {
        src: 'collapseAnimation',
        onDone: 'collapsed'
      }
    },
    expanded: {
      initial: 'entering',
      on: {
        COLLAPSE: 'collapsing',
        UPDATE_HAS_VIDEO: {
          target: ['.entering'],
          internal: true,
          actions: ['updateHasVideo'],
        },
      },
      states: {
        entering: {
          always: [
            {
              target: 'empty', cond: 'isExpandedEmpty'
            },
            {
              target: 'playback', cond: 'isExpandedPlayback'
            },
          ]
        },
        empty: {

        },
        playback: {

        },
      }
    },
  },
  on: {

  }
}, {
  guards: {
    isCollapsedEmpty: ctx => !ctx.hasVideo,
    isCollapsedExisting: ctx => ctx.hasVideo,
    isExpandedEmpty: ctx => !ctx.hasVideo,
    isExpandedPlayback: ctx => ctx.hasVideo,
  }
})
