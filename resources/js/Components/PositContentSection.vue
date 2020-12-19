<template>
  <div class="relative">

    <EditorContent
      :editor="editor"
      @handleControlHitUp="handleControlHitUp"
      @handleControlHitDown="handleControlHitDown"
      @handleAddBlockAbove="handleAddBlockAbove"
      @handleAddBlockBelow="handleAddBlockBelow"
      @handleDeleteBlock="handleDeleteBlock" />

<!--     <EditorNewLineFloatingMenu
      :editor="editor"
    /> -->

<!--     <EditorSelectMenuBubble
      :editor="editor"
      :keep-in-bounds="keepInBounds"/> -->

  </div>
</template>

<script>
// import EditorSelectMenuBubble from '@/PositEditor/Menus/EditorSelectMenuBubble'
import EditorNewLineFloatingMenu from '@/PositEditor/Menus/EditorNewLineFloatingMenu'
import { Editor, EditorContent, defaultExtensions } from '@tiptap/vue-starter-kit'
// import {
//   Blockquote,
//   BulletList,
//   CodeBlock,
//   Image,
//   HardBreak,
//   Heading,
//   ListItem,
//   OrderedList,
//   TodoItem,
//   TodoList,
//   Bold,
//   Code,
//   Italic,
//   Link,
//   Strike,
//   Underline,
//   History,
//   Focus,
//   // HorizontalRule,
// } from 'tiptap-extensions'
import PositBlockNode from '@/PositEditor/Nodes/PositBlockNode'
import PositLayoutDocOne from '@/PositEditor/Nodes/PositLayoutDocOne'
import Dropcursor from '@tiptap/extension-dropcursor'
import Gapcursor from '@tiptap/extension-gapcursor'
// import Document from '@tiptap/extension-document'
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
  props: {
    editable: {
      type: Boolean,
      default: true
    }
  },
  components: {
    EditorContent,
    // EditorNewLineFloatingMenu,
    // EditorSelectMenuBubble
  },
  data () {
    return {
      keepInBounds: true,
      editor: new Editor({
        editable: this.editable,
        editorProps: {
          attributes: {
            class: 'outline-none space-y-2/12'
          }
        },
        extensions: [
          ...defaultExtensions(),
          // PositLayoutDocOne,
          PositBlockNode,
          // Dropcursor,
          // Gapcursor,
          // Paragraph,
          // Text,
          // History,
          // Bold,
          // Italic,
          // Code,
          // CodeBlock,
          // Heading,
          // HardBreak,
          // Strike,
          // Blockquote,
          // HorizontalRule,
          // BulletList,
          // OrderedList,
          // ListItem
        ],
        // extensions: [
        //   new PositLayoutDocOne(),
        //   new PositBlockNode(),
        //   new Blockquote(),
        //   new BulletList(),
        //   new CodeBlock(),
        //   new HardBreak(),
        //   new Heading({ levels: [1, 2, 3] }),
        //   new ListItem(),
        //   new OrderedList(),
        //   // new HorizontalRule(),
        //   new TodoItem(),
        //   new TodoList(),
        //   new Link(),
        //   new Image(),
        //   new Bold(),
        //   new Code(),
        //   new Italic(),
        //   new Strike(),
        //   new Underline(),
        //   new History(),
        //   new Focus({
        //     className: 'has-focus',
        //     nested: false,
        //   })
        // ],
        onInit: this.onEditorInit,
        onTransaction: this.onEditorTransaction,
        onUpdate: this.onEditorUpdate,
        onFocus: this.onEditorFocus,
        onBlur: this.onEditorBlur,
        onPaste: this.onEditorPaste,
        onDrop: this.onEditorDrop,
      })
    }
  },
  watch: {
    editable() {
      this.editor.setOptions({
        editable: this.editable,
      })
    },
  },
  methods: {
    onEditorInit ({ state, view }) {
      // console.log('onEditorInit')
      // console.log(state)
      // console.log(view)
    },
    onEditorUpdate (transaction) {
      console.log('onEditorUpdate', this.editor.getJSON())

      this.$emit('update', { state: this.editor.state, transaction })
    },
    onEditorFocus ({ event,  state, view }) {
      console.log('onEditorFocus')
      // console.log(event, state, view)
    },
    onEditorBlur ({ event,  state, view }) {
      console.log('onEditorBlur')
      // console.log(event, state, view)
    },
    onEditorPaste () {
      // console.log(`New content was added from the user's clipboard!`)
    },
    onEditorDrop (view, event, slice, moved) {
      // console.log(`onEditorDrop`)
      // console.log(view, event, slice, moved)
    },
    onEditorTransaction (params) {
      // console.log(`onEditorTransaction`)
      // console.log(params)
    },
    handleControlHitUp ({ node, view, startPos }) {
      if (!this.editable) return

      // console.log(`handleControlHitUp`, node, view, startPos)
      const pos = startPos
      const { nodeBefore } = view.state.doc.resolve(pos)

      if (!nodeBefore) {
        // console.log('no node before...')
        return
      }

      // Do prosemirror transaction to move node up!
      const newPosition = pos - nodeBefore.nodeSize;
      const from = pos
      const to = pos + node.nodeSize
      // console.log('newPosition: ', newPosition)
      // console.log('from: ', from)
      // console.log('to: ', to)

      const transactionMoveNodeUp = view.state.tr
        .replace(from, to)
        .insert(newPosition, node)

      // console.log(transactionMoveNodeUp)

      view.dispatch(transactionMoveNodeUp)
    },
    handleControlHitDown ({ node, view, startPos }) {
      if (!this.editable) return

      // TODO this whole thing is broken and I dont know why...
      // console.log(`handleControlHitDown`, node, view, startPos)
      const pos = startPos

      const childAfter = view.state.doc.childAfter(startPos + node.nodeSize)
      // console.log('childAfter: ', childAfter)

      if (childAfter.node == null) {
        // console.log('cannot move down because its the last one/no node after...')
        return
      }

      const transactionMoveNodeDown = view.state.tr
        .replace(pos, pos + node.nodeSize)
        .insert(pos + childAfter.node.nodeSize, node)

      view.dispatch(transactionMoveNodeDown)
    },
    handleAddBlockAbove ({ node, view, startPos }) {
      if (!this.editable) return
      // console.log('handleAddBlockAbove')
      const transaction = view.state.tr.insert(startPos, this.editor.schema.node("posit_block", null, [this.editor.schema.node("paragraph")]))
      view.dispatch(transaction)
    },
    handleAddBlockBelow ({ node, view, startPos }) {
      if (!this.editable) return
      // console.log('handleAddBlockBelow: ', node, view, startPos)
      const transaction = view.state.tr.insert(startPos + node.nodeSize, this.editor.schema.node("posit_block", null, [this.editor.schema.node("paragraph")]))
      view.dispatch(transaction)
    },
    handleDeleteBlock ({ node, view, startPos }) {
      if (!this.editable) return
      // console.log('handleDeleteBlock: ', node, view, startPos)
      const transaction = view.state.tr.replace(startPos, startPos + node.nodeSize)
      view.dispatch(transaction)
    },
  },
  beforeDestroy() {
    this.editor.destroy()
  },
}
</script>
