import { Machine } from 'xstate'

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
        empty: {
          on: {
            RECORD: 'recording'
          }
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
  }
}, {
  guards: {
    isCollapsedEmpty: ctx => !ctx.hasVideo,
    isCollapsedExisting: ctx => ctx.hasVideo && !ctx.isUploading,
    isCollapsedUploading: ctx => ctx.isUploading,
    isCollapsedUploadingFailed: ctx => ctx.uploadFailed,
    isExpandedEmpty: ctx => !ctx.hasVideo,
    isExpandedPlayback: ctx => ctx.hasVideo && !ctx.isUploading && !ctx.isRecording,
    isExpandedUploading: ctx => ctx.isUploading,
    isExpandedUploadingFailed: ctx => !ctx.isUploading && ctx.uploadFailed,
  }
})
