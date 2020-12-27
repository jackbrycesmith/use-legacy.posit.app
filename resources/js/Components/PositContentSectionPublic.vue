<template>
  <div class="relative">

    <EditorContent
      :editor="editor"/>

  </div>
</template>

<script>
import { Editor, EditorContent } from '@tiptap/vue-starter-kit'
import PositBlockNode from '@/PositEditor/Nodes/PositBlockNode'
import PositLayoutDocOne from '@/PositEditor/Nodes/PositLayoutDocOne'
import Dropcursor from '@tiptap/extension-dropcursor'
import Gapcursor from '@tiptap/extension-gapcursor'
import Paragraph from '@tiptap/extension-paragraph'
import Text from '@tiptap/extension-text'
import History from '@tiptap/extension-history'
import Bold from '@tiptap/extension-bold'
import Italic from '@tiptap/extension-italic'
import Code from '@tiptap/extension-code'
import CodeBlock from '@tiptap/extension-code-block'
import Heading from '@tiptap/extension-heading'
import HardBreak from '@tiptap/extension-hard-break'
import Strike from '@tiptap/extension-strike'
import Blockquote from '@tiptap/extension-blockquote'
import HorizontalRule from '@tiptap/extension-horizontal-rule'
import BulletList from '@tiptap/extension-bullet-list'
import OrderedList from '@tiptap/extension-ordered-list'
import ListItem from '@tiptap/extension-list-item'

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
          PositLayoutDocOne,
          Dropcursor,
          Gapcursor,
          Paragraph,
          Text,
          History,
          Bold,
          Italic,
          Code,
          CodeBlock,
          Heading,
          HardBreak,
          Strike,
          Blockquote,
          HorizontalRule,
          BulletList,
          OrderedList,
          ListItem,
          PositBlockNode,
        ],
        onCreate: this.onEditorInit,
      })
    }
  },
  methods: {
    onEditorInit () {

    },
  },
  beforeDestroy() {
    this.editor.destroy()
  },
}
</script>
