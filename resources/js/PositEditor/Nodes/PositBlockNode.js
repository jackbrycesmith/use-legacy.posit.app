import { Node, mergeAttributes } from '@tiptap/core'
import { VueRenderer } from '@tiptap/vue'
import PositBlock from '@/PositEditor/Components/PositBlock'

export default Node.create({

  name: 'posit_block',

  group: 'block',

  atom: true,

  content: 'block*',

  selectable: false,

  draggable: false,

  addNodeView() {
    return VueRenderer(PositBlock)
  },

  renderHTML({ HTMLAttributes }) {
    return ['div', mergeAttributes(HTMLAttributes, { 'data-posit-type': 'posit_block' }), 0]
  },

  parseHTML() {
    return [
      {
        tag: 'div[data-posit-type="posit_block"]',
      },
    ]
  },

  addAttributes() {
    return {
      title: {
        default: null,
      },
      expanded: {
        default: true
      },
      positType: {
        default: 'posit_block',
        // Customize the HTML parsing (for example, to load the initial content)
        parseHTML: element => {
          return {
            positType: element.getAttribute('data-posit-type'),
          }
        },
        // … and customize the HTML rendering.
        renderHTML: attributes => {
          return {
            'data-posit-type': attributes.positType,
          }
        },
      }
    }
  },

})

// export default class PositBlockNode extends Node {

//   get name() {
//     return 'posit_block'
//   }

//   get view() {
//     return PositBlock
//   }

//   get schema() {
//     return {
//       attrs: {
//         title: {
//           default: null,
//         },
//         expanded: {
//           default: true
//         }
//       },
//       group: 'block',
//       content: 'block*',
//       selectable: false,
//       draggable: false,
//       toDOM: () => ['div', { 'data-posit-type': this.name }, 0],
//       parseDOM: [{
//         tag: `[data-posit-type="${this.name}"]`,
//         getAttrs: dom => ({
//           positTitle: dom.getAttribute('posit-title'),
//           expanded: dom.getAttribute('data-expanded'),
//         }),
//       }],
//     }
//   }
// }
