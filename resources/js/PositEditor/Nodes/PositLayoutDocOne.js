import { Node } from '@tiptap/core'

export default Node.create({
  name: 'document',
  topNode: true,
  content: 'posit_block*',
})
