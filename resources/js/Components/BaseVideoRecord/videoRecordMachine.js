import { Machine, assign } from 'xstate'

export const videoRecordMachine = Machine({
  id: 'videoRecord',
  context: {
    hasVideo: false,
    isUploading: false,
    isProcessing: false, // i.e. just after upload
    uploadFailed: false,
    recordedFile: null
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
          on: {
            UPLOAD_SUCCESS: {
              target: 'processing',
              actions: ['setUploadCompletedContext']
            },
            UPLOAD_FAILED: {
              target: 'uploadingFailed',
              actions: ['setUploadFailedContext']
            },
          }
        },
        processing: {

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
            RECORDED: {
              internal: true,
              target: 'recordedConfirmUpload',
              actions: ['updateRecordedFile']
            },
            RECORDING_CANCEL: 'playback',
          }
        },
        recordedConfirmUpload: {
          on: {
            CONFIRM: {
              internal: true,
              target: 'uploading',
              actions: [
                assign({
                  isUploading: (context, event) => true
                }),
                'uploadVideo'
              ]
            },
            RECORD_AGAIN: {
              internal: true,
              target: 'recording',
              actions: assign({
                recordedFile: (context, event) => null
              })
            }
          }
        },
        uploading: {
          on: {
            UPLOAD_SUCCESS: {
              target: 'playback',
              actions: ['setUploadCompletedContext']
            },
            UPLOAD_FAILED: {
              target: 'uploadingFailed',
              actions: ['setUploadFailedContext']
            }
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
    // Upload state fallback e.g. if happened when collapsing/expanding, then the entering guards should put it in the correct state...
    UPLOAD_SUCCESS: {
      actions: ['setUploadCompletedContext']
    },
    UPLOAD_FAILED: {
      actions: ['setUploadFailedContext']
    },
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
    isCollapsedEmpty: ctx => !ctx.hasVideo && !!!ctx.recordedFile,
    isCollapsedExisting: ctx => ctx.hasVideo && !ctx.isUploading,
    isCollapsedUploading: ctx => ctx.isUploading,
    isCollapsedUploadingFailed: ctx => ctx.uploadFailed,
    isExpandedRecording: ctx => !ctx.hasVideo && !ctx.isUploading && !ctx.uploadFailed,
    isExpandedPlayback: ctx => ctx.hasVideo && !ctx.isUploading && !ctx.uploadFailed,
    isExpandedUploading: ctx => ctx.isUploading && !ctx.uploadFailed,
    isExpandedUploadingFailed: ctx => ctx.uploadFailed,
  }
})
