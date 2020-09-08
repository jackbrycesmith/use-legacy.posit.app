import { Machine, assign } from 'xstate'

export const videoRecordMachine = Machine({
  id: 'videoRecord',
  context: {
    hasVideo: false,
    isUploading: false,
    isRecording: false,
    uploadFailed: false,
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
          // actions: assign({
          //   hasVideo: (context) => {
          //     return !context.hasVideo
          //   }
          // })
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
            {
              target: 'uploading', cond: 'isCollapsedUploading'
            },
            {
              target: 'uploadingFailed', cond: 'isCollapsedUploadingFailed'
            },
          ]
        },
        empty: {

        },
        existing: {

        },
        uploading: {

        },
        uploadingFailed: {

        }
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
          // actions: assign({
          //   hasVideo: (context) => {
          //     return !context.hasVideo
          //   }
          // })
        },
      },
      states: {
        entering: {
          always: [
            {
              target: 'playback', cond: 'isExpandedPlayback'
            },
            {
              target: 'recording', cond: 'isExpandedRecording'
            },
            {
              target: 'uploading', cond: 'isExpandedUploading'
            },
            {
              target: 'uploadingFailed', cond: 'isExpandedUploadingFailed'
            },
          ]
        },
        playback: {
          on: {
            RECORD: 'recording'
          }
        },
        recording: {
          on: {
            RECORDED: 'recordedConfirmUpload'
          }
        },
        recordedConfirmUpload: {
          on: {
            CONFIRM: 'uploading'
          }
        },
        uploading: {
          on: {
            UPLOAD_SUCCESS: 'playback',
            UPLOAD_FAILED: 'uploadingFailed'
          }
        },
        uploadingFailed: {
          on: {
            RETRY_UPLOAD: 'uploading'
          }
        }
      }
    },
  },
  on: {
    // EXPAND: '.expanding',
    // COLLAPSE: '.collapsing'
    // TOGGLE_HAS_VIDEO: {
    //   target: ['collapsed'],
    //   internal: true,
    //   actions: assign({
    //     hasVideo: (context) => {
    //       return !context.hasVideo
    //     }
    //   })
    // },
    // TOGGLE_HAS_VIDEO: {
    //   target: ['expanded'],
    //   internal: true,
    //   actions: assign({
    //     hasVideo: (context) => {
    //       return !context.hasVideo
    //     }
    //   })
    // }
  }
}, {
  guards: {
    isCollapsedEmpty: ctx => !ctx.hasVideo,
    isCollapsedExisting: ctx => ctx.hasVideo && !ctx.isUploading,
    isCollapsedUploading: ctx => ctx.isUploading,
    isCollapsedUploadingFailed: ctx => ctx.uploadFailed,
    isExpandedRecording: ctx => !ctx.hasVideo,
    isExpandedPlayback: ctx => ctx.hasVideo && !ctx.isUploading && !ctx.isRecording,
    isExpandedUploading: ctx => ctx.isUploading,
    isExpandedUploadingFailed: ctx => !ctx.isUploading && ctx.uploadFailed,
  }
})
