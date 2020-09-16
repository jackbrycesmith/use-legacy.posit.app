import { Machine } from 'xstate'

export const videoRecordMachine = Machine({
  id: 'videoRecord',
  context: {
    hasVideo: false,
    isUploading: false,
    isProcessing: false,
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
        },
        PROCESSING: {
          target: ['.entering'],
          internal: true,
          actions: ['setIsProcessingContext'],
        },
        PROCESSING_COMPLETED: {
          target: ['.entering'],
          internal: true,
          actions: ['setIsNotProcessingContext'],
        }
      },
      states: {
        entering: {
          always: [
            {
              target: 'empty', cond: 'isCollapsedEmpty'
            },
            {
              target: 'processing', cond: 'isCollapsedProcessing'
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
              actions: ['setUploadCompletedContext', 'setIsProcessingContext']
            },
            UPLOAD_FAILED: {
              target: 'uploadingFailed',
              actions: ['setUploadFailedContext']
            },
          }
        },
        processing: {
          on: {
            PROCESSING_COMPLETED: {
              target: 'existing',
              actions: ['setIsNotProcessingContext']
            },
          }
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
        },
        PROCESSING_COMPLETED: {
          target: ['.entering'],
          internal: true,
          actions: ['setIsNotProcessingContext'],
        },
        PROCESSING: {
          target: ['.entering'],
          internal: true,
          actions: ['setIsProcessingContext'],
        },
      },
      states: {
        entering: {
          always: [
            {
              target: 'empty', cond: 'isExpandedEmpty'
            },
            {
              target: 'processing', cond: 'isExpandedProcessing'
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
        processing: {
          on: {
            RECORD: {
              internal: true,
              target: 'recording',
              actions: ['setIsNotProcessingContext']
            }
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
            RECORDING_ERROR: 'recordError'
          }
        },
        recordError: {

        },
        recordedConfirmUpload: {
          on: {
            CONFIRM_UPLOAD: {
              internal: true,
              target: 'uploading',
              actions: [
                'setUploadingContext',
                'uploadVideo'
              ]
            },
            RECORD_AGAIN: {
              internal: true,
              target: 'recording',
              actions: ['setRecordAgainContext']
            }
          }
        },
        uploading: {
          on: {
            UPLOAD_SUCCESS: {
              target: 'processing',
              actions: ['setUploadCompletedContext', 'setIsProcessingContext']
            },
            UPLOAD_FAILED: {
              target: 'uploadingFailed',
              actions: ['setUploadFailedContext']
            }
          }
        },
        uploadingFailed: {
          on: {
            CONFIRM_UPLOAD: {
              internal: true,
              target: 'uploading',
              actions: [
                'setUploadingContext',
                'uploadVideo'
              ]
            },
            RECORD_AGAIN: {
              internal: true,
              target: 'recording',
              actions: ['setRecordAgainContext']
            }
          }
        }
      }
    },
  },
  on: {
    UPLOAD_SUCCESS: {
      actions: ['setUploadCompletedContext']
    },
    UPLOAD_FAILED: {
      actions: ['setUploadFailedContext']
    },

    PROCESSING: {
      actions: ['setIsProcessingContext'],
    },

    PROCESSING_COMPLETED: {
      actions: ['setIsNotProcessingContext']
    },

    INITIALLY_IS_PROCESSING: {
      actions: ['setIsProcessingContext']
    },

    INITIALLY_IS_NOT_PROCESSING: {
      actions: ['setIsNotProcessingContext']
    },
  }
}, {
  guards: {
    isCollapsedProcessing: ctx => ctx.isProcessing,
    isCollapsedEmpty: ctx => !ctx.hasVideo && !!!ctx.recordedFile,
    isCollapsedExisting: ctx => ctx.hasVideo && !ctx.isUploading,
    isCollapsedUploading: ctx => ctx.isUploading,
    isCollapsedUploadingFailed: ctx => ctx.uploadFailed,
    isExpandedProcessing: ctx => ctx.isProcessing,
    isExpandedEmpty: ctx => !ctx.hasVideo && !!!ctx.recordedFile,
    isExpandedRecording: ctx => !ctx.hasVideo && !ctx.isUploading && !ctx.uploadFailed,
    isExpandedPlayback: ctx => ctx.hasVideo && !ctx.isUploading && !ctx.uploadFailed,
    isExpandedUploading: ctx => ctx.isUploading && !ctx.uploadFailed,
    isExpandedUploadingFailed: ctx => ctx.uploadFailed,
  }
})
