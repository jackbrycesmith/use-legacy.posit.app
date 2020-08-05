import { Node } from 'tiptap'
import PositBlock from '@/PositEditor/Components/PositBlock'

export default class PositBlockNode extends Node {

  get name() {
    return 'posit_block'
  }

  get view() {
    return PositBlock
  }

  get schema() {
    return {
      attrs: {
        title: {
          default: null,
        },
      },
      group: 'block',
      content: 'block*',
      selectable: false,
      draggable: false,
      toDOM: () => ['div', { 'data-posit-type': this.name }, 0],
      parseDOM: [{
        tag: `[data-posit-type="${this.name}"]`,
        getAttrs: dom => ({
          positTitle: dom.getAttribute('posit-title'),
        }),
      }],
    }
  }
}
