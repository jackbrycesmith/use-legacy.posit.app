import { Machine, assign } from 'xstate'

export const publicPositMachine = Machine({
  id: 'publicPosit',
  context: {
    state: 'published',
    type: 'accept_only',
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
              target: 'draft', cond: 'isDraftState',
            },
            {
              target: 'published', cond: 'isPublishedState',
            },
            {
              target: 'accepted', cond: 'isAcceptedState',
            },
            {
              target: 'expired', cond: 'isExpiredState',
            },
            {
              target: 'amending', cond: 'isAmendingState',
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
              target: 'published', cond: 'isPublishedState',
            },
            {
              target: 'accepted', cond: 'isAcceptedState',
            },
            {
              target: 'expired', cond: 'isExpiredState',
            },
            {
              target: 'amending', cond: 'isAmendingState',
            },
          ]
        },

        published: {
          initial: 'entering',
          states: {
            entering: {
              always: [
                {
                  target: 'acceptAndPayable', cond: 'isAcceptAndPayType'
                },
                {
                  target: 'acceptable', cond: 'isAcceptOnlyType'
                },
              ]
            },
            acceptAndPayable: {
              on: {
                ACCEPT_WITH_PAYMENT: {
                  target: ['acceptingWithPayment']
                }
              }
            },
            acceptable: {
              on: {
                ACCEPT: {
                  target: ['accepting']
                }
              }
            },
            acceptingWithPayment: {
              invoke: {
                src: 'acceptWithPaymentAction',
                onDone: {
                  target: 'entering'
                },
                onError: {
                  target: 'entering'
                }
              }
            },
            accepting: {
              invoke: {
                src: 'acceptAction',
                onDone: {
                  target: '#unlockedAccepted',
                  actions: ['setAcceptedState']
                },
                onError: {
                  target: 'entering'
                }
              }
            },
          }
        },

        accepted: {
          id: 'unlockedAccepted'

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
    isDraftState: ctx => ctx.state === 'draft',
    isPublishedState: ctx => ctx.state === 'published',
    isAcceptedState: ctx => ctx.state === 'accepted',
    isExpiredState: ctx => ctx.state === 'expired',
    isAmendingState: ctx => ctx.state === 'amending',
    isAcceptOnlyType: ctx => ctx.type === 'accept_only',
    isAcceptAndPayType: ctx => ctx.type === 'accept_and_pay',
  },
  actions: {
    setAcceptedState: assign({ state: context => context.state = 'accepted' }),
    setAcceptOnlyType: assign({ type: context => context.type = 'accept_only' }),
    setAcceptAndPayType: assign({ type: context => context.type = 'accept_and_pay' }),
  }
})
