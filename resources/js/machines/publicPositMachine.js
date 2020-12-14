import { Machine } from 'xstate'

export const publicPositMachine = Machine({
  id: 'publicPosit',
  context: {
    status: 'published',
    isReturnFromStripeCheckoutSuccess: false
  },
  initial: 'unlocked',
  states: {
    locked: {
      initial: 'entering',
      states: {
        entering: {
          always: [
            {
              target: 'draft', cond: 'isDraftStatus',
            },
            {
              target: 'published', cond: 'isPublishedStatus',
            },
            {
              target: 'accepted', cond: 'isAcceptedStatus',
            },
            {
              target: 'expired', cond: 'isExpiredStatus',
            },
            {
              target: 'amending', cond: 'isAmendingStatus',
            },
          ]
        },

        draft: {

        },

        published: {

        },

        accepted: {

        },

        expired: {

        },

        amending: {

        },
      }
    },

    unlocked: {
      initial: 'entering',
      states: {
        entering: {
          always: [
            {
              target: 'published', cond: 'isPublishedStatus',
            },
            {
              target: 'accepted', cond: 'isAcceptedStatus',
            },
            {
              target: 'expired', cond: 'isExpiredStatus',
            },
            {
              target: 'amending', cond: 'isAmendingStatus',
            },
          ]
        },

        published: {
          initial: 'ready',
          states: {
            ready: {
              on: {
                ACCEPT_WITH_PAYMENT: {
                  target: ['acceptingWithPayment']
                }
              }
            },
            acceptingWithPayment: {
              invoke: {
                src: 'acceptWithPaymentAction',
                onDone: {
                  target: 'ready'
                },
                onError: {
                  target: 'ready'
                }
              }
            }
          }
        },

        accepted: {

        },

        expired: {

        },

        amending: {

        },
      }
    },

    unlocking: {
      invoke: {
        src: 'unlockAnimation',
        onDone: 'unlocked'
      }
    },

    locking: {
      invoke: {
        src: 'lockAnimation',
        onDone: 'locked'
      }
    },
  }
}, {
  guards: {
    isDraftStatus: ctx => ctx.status === 'draft',
    isPublishedStatus: ctx => ctx.status === 'published',
    isAcceptedStatus: ctx => ctx.status === 'accepted',
    isExpiredStatus: ctx => ctx.status === 'expired',
    isAmendingStatus: ctx => ctx.status === 'amending',
  },
})
