import { Machine, assign } from 'xstate'

export const proposalSlideOverContentMachine = Machine({
  id: 'proposalSlideOverContent',
  context: {
    isPublished: false,
    canPublish: false,
    hasSufficientCredits: true, // Hardcode for now
    isPublishing: false,
  },
  initial: 'defaultView',
  states: {
    defaultView: {
      on: {
        PREPARE_TO_PUBLISH: 'confirmPublishView'
      }
    },
    confirmPublishView: {
      initial: 'entering',
      on: {
        BACK_TO_DEFAULT_VIEW: 'defaultView'
      },
      states: {
        entering: {
          always: [
            {
              target: 'publishSuccess', cond: 'isPublished'
            },
            {
              target: 'cannotPublish', cond: 'cannotPublish'
            },
            {
              target: 'canPublish', cond: 'canPublish'
            },
          ]
        },
        cannotPublish: {
          on: {
            CAN_PUBLISH: {
              target: ['entering'],
              actions: ['setCanPublish'],
            },
          }
        },
        canPublish: {
          initial: 'ready',
          on: {
            CANNOT_PUBLISH: {
              target: ['entering'],
              actions: ['setCannotPublish'],
            },
          },
          states: {
            ready: {
              on: {
                PUBLISH: {
                  target: ['publishing'],
                  actions: ['setIsPublishing']
                }
              }
            },
            publishing: {
              invoke: {
                src: 'publishAction',
                onDone: {
                  target: '#publishSuccess',
                  actions: ['setIsNotPublishing', 'setIsPublished']
                },
                onError: {
                  target: 'publishError',
                  actions: ['setIsNotPublishing']
                }
              }
            },
            publishError: {
              on: {
                PUBLISH: {
                  target: ['publishing'],
                  actions: ['setIsPublishing']
                }
              }
            }
          }
        },
        publishSuccess: {
          id: 'publishSuccess'

        }
      }
    },
  },
  on: {
    CAN_PUBLISH: {
      actions: ['setCanPublish']
    },
    CANNOT_PUBLISH: {
      actions: ['setCannotPublish']
    },
    IS_PUBLISHED: {
      actions: ['setIsPublished']
    },
    IS_NOT_PUBLISHED: {
      actions: ['setIsNotPublished']
    },
  }
}, {
  guards: {
    cannotPublish: ctx => ctx.canPublish === false,
    canPublish: ctx => ctx.canPublish,
    isPublished: ctx => ctx.isPublished,
  },
  actions: {
    setCanPublish: assign({ canPublish: context => context.canPublish = true }),
    setCannotPublish: assign({ canPublish: context => context.canPublish = false }),
    setIsPublishing: assign({ isPublishing: context => context.isPublishing = true }),
    setIsNotPublishing: assign({ isPublishing: context => context.isPublishing = false }),
    setIsPublished: assign({ isPublished: context => context.isPublished = true }),
    setIsNotPublished: assign({ isPublished: context => context.isPublished = false }),
  }
})
