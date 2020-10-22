import { Machine } from 'xstate'

export const proposalSlideOverContentMachine = Machine({
  id: 'proposalSlideOverContent',
  context: {
    isPublished: false,
    canPublish: false,
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
      initial: 'cannotPublish',
      on: {
        BACK_TO_DEFAULT_VIEW: 'defaultView'
      },
      states: {
        // entering: {
        //   always: [
        //     {
        //       target: 'cannotPublish', cond: 'cannotPublish'
        //     },
        //     {
        //       target: 'canPublish', cond: 'canPublish'
        //     },
        //   ]
        // },
        cannotPublish: {

        },
        canPublish: {

        },
        publishNetworkError: {

        },
        publishSuccess: {

        }
      }
    },
  },
  on: {

  }
}, {
  guards: {
    // cannotPublish: ctx => ctx.canPublish === false,
    // canPublish: ctx => ctx.canPublish,
  }
})
