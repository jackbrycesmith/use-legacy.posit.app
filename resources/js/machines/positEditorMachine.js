import { Machine, assign } from 'xstate'

export const positEditorMachine = Machine({
  id: 'positEditor',
  context: {
    canEdit: false
  },
  initial: 'entering',
  states: {
    entering: {
      always: [
        {
          target: 'editable', cond: 'isEditable'
        },
        {
          target: 'readOnly', cond: 'isReadOnly'
        },
      ]
    },

    editable: {
      on: {
        CANNOT_EDIT: {
          target: ['entering'],
          actions: ['setCannotEdit']
        }
      }
    },

    readOnly: {
      on: {
        CAN_EDIT: {
          target: ['entering'],
          actions: ['setCanEdit']
        }
      }
    }
  }
}, {
  guards: {
    isEditable: ctx => ctx.canEdit === true,
    isReadOnly: ctx => ctx.canEdit === false,
  },
  actions: {
    setCanEdit: assign({ canEdit: context => context.canEdit = true }),
    setCannotEdit: assign({ canEdit: context => context.canEdit = false }),
  }
})
