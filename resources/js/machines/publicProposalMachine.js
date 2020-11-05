import { Machine } from 'xstate'

export const publicProposalMachine = Machine({
  id: 'publicProposal',
  context: {
    status: 'proposal_published',
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
              target: 'voided', cond: 'isVoidedStatus',
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

        voided: {

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
              target: 'voided', cond: 'isVoidedStatus',
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

        voided: {

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
    isDraftStatus: ctx => ctx.status === 'proposal_draft',
    isPublishedStatus: ctx => ctx.status === 'proposal_published',
    isAcceptedStatus: ctx => ctx.status === 'proposal_accepted',
    isExpiredStatus: ctx => ctx.status === 'proposal_expired',
    isVoidedStatus: ctx => ctx.status === 'proposal_void',
  },
})
