<template>
  <div class="relative">

    <EditorContent
      :editor="editor"/>

  </div>
</template>

<script>
import { Editor, EditorContent } from 'tiptap'
import {
  Blockquote,
  BulletList,
  CodeBlock,
  Image,
  HardBreak,
  Heading,
  ListItem,
  OrderedList,
  TodoItem,
  TodoList,
  Bold,
  Code,
  Italic,
  Link,
  Strike,
  Underline,
  History,
  Focus,
  // HorizontalRule,
} from 'tiptap-extensions'
import PositBlockNode from '@/PositEditor/Nodes/PositBlockNode'
import PositLayoutDocOne from '@/PositEditor/Nodes/PositLayoutDocOne'

export default {
  components: {
    EditorContent,
  },
  data () {
    return {
      keepInBounds: true,
      editor: new Editor({
        editable: false,
        editorProps: {
          attributes: {
            class: 'outline-none space-y-2/12'
          }
        },
        extensions: [
          new PositLayoutDocOne(),
          new PositBlockNode(),
          new Blockquote(),
          new BulletList(),
          new CodeBlock(),
          new HardBreak(),
          new Heading({ levels: [1, 2, 3] }),
          new ListItem(),
          new OrderedList(),
          // new HorizontalRule(),
          new TodoItem(),
          new TodoList(),
          new Link(),
          new Image(),
          new Bold(),
          new Code(),
          new Italic(),
          new Strike(),
          new Underline(),
          new History(),
          new Focus({
            className: 'has-focus',
            nested: false,
          })
        ],
        onInit: this.onEditorInit,
      })
    }
  },
  methods: {
    onEditorInit ({ state, view }) {
      // console.log('onEditorInit')
      // console.log(state)
      // console.log(view)
    },
  },
  beforeDestroy() {
    this.editor.destroy()
  },
}
</script>
