import { Machine, assign } from 'xstate'

export const proposalSlideOverContentMachine = Machine({
  id: 'proposalSlideOverContent',
  context: {
    isPublished: false,
    canPublish: false,
    hasSufficientCredits: true, // Hardcode for now
    isPublishing: false,
    publishFailed: false
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
          on: {
            CANNOT_PUBLISH: {
              target: ['entering'],
              actions: ['setCannotPublish'],
            },
          }
        },
        publishNetworkError: {

        },
        publishSuccess: {

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
    }
  }
}, {
  guards: {
    cannotPublish: ctx => ctx.canPublish === false,
    canPublish: ctx => ctx.canPublish,
  },
  actions: {
    setCanPublish: assign({ canPublish: context => context.canPublish = true }),
    setCannotPublish: assign({ canPublish: context => context.canPublish = false }),
  }
})
