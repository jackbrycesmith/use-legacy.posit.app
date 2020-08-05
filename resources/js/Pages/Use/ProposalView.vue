<template>
  <fragment>

    <!-- <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8"> -->
      <!-- We've used 3xl here, but feel free to try other max-widths based on your needs -->
      <!-- <div class="max-w-3xl mx-auto"> -->
        <!-- Content goes here -->
      <!-- </div> -->
    <!-- </div> -->

    <editor-content
      :editor="editor"
      @handleControlHitUp="handleControlHitUp"
      @handleControlHitDown="handleControlHitDown"
    />

    <ProposalSlideOver ref="proposalSlideOver" @updatePressed="handleUpdatePressed"/>
    <FirstWelcomeModal ref="firstWelcomeModal"/>
    <LoginModal ref="loginModal"/>
  </fragment>
</template>

<script>
import ProposalSlideOver from '@/SlideOvers/ProposalSlideOver'
import FirstWelcomeModal from '@/Modals/FirstWelcomeModal'
import LoginModal from '@/Modals/LoginModal'
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
} from 'tiptap-extensions'
import PositBlockNode from '@/PositEditor/Nodes/PositBlockNode'
import PositLayoutDocOne from '@/PositEditor/Nodes/PositLayoutDocOne'

export default {
  components: { ProposalSlideOver, FirstWelcomeModal, LoginModal, EditorContent },
  props: {
    proposal: { type: Object }
  },
  metaInfo () {
    return {
      htmlAttrs: {
        class: ['h-full', this.htmlBgColorClass]
      }
    }
  },
  data () {
    return {
      keepInBounds: true,
      editor: new Editor({
        editorProps: {
          attributes: {
            class: 'h-screen outline-none space-y-2/12'
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
        ],
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
  computed: {
    proposalUuid () {
      return this.proposal.data.uuid
    },
    htmlBgColorClass () {
      return 'bg-gray-50'
    }
  },
  mounted () {
    // TODO maybe do component dynamic import loads inside created () or something so we can do stuff when its loaded
    setTimeout(() => {
      // this.$refs.firstWelcomeModal.show()
      this.$refs.proposalSlideOver.show()
    }, 1200)

    console.log(this.$page.user)

    setTimeout(() => {
      // this.$refs.firstWelcomeModal.show()
      // this.$refs.loginModal.show()
    }, 2000)

  },
  watch: {
    proposal: {
      immediate: true,
      handler (value) {
        this.editor.setContent(
          // `
          //   <div data-posit-type="posit_block">
          //     Test content 1...
          //   </div>

          //   <div data-posit-type="posit_block">
          //     Test content 2...
          //   </div>

          //   <div data-posit-type="posit_block">
          //     Test content 3...
          //   </div>
          // `
          value.data?.content?.content
        )
      }
    }
  },
  methods: {
    onEditorInit ({ state, view }) {
      console.log('onEditorInit')
      console.log(state)
      console.log(view)
    },
    onEditorUpdate ({ state, getHTML, getJSON, transaction }) {
      console.log('onEditorUpdate')
      console.log(state, transaction)
      console.log(getHTML(), getJSON())
    },
    onEditorFocus ({ event,  state, view }) {
      console.log('onEditorFocus')
      console.log(event, state, view)
    },
    onEditorBlur ({ event,  state, view }) {
      console.log('onEditorBlur')
      console.log(event, state, view)
    },
    onEditorPaste () {
      console.log(`New content was added from the user's clipboard!`)
    },
    onEditorDrop (view, event, slice, moved) {
      console.log(`onEditorDrop`)
      console.log(view, event, slice, moved)
    },
    onEditorTransaction (params) {
      console.log(`onEditorTransaction`)
      console.log(params)
    },
    async handleUpdatePressed () {
      // TODO this is not production ready
      const response = this.$http.put(
        this.$route('use.submit.upsert-proposal-content', { proposal: this.proposalUuid }),
        this.editor.getJSON()
      )
      console.log(response)
    },
    handleControlHitUp ({ node, view, startPos }) {
      console.log(`handleControlHitUp`, node, view, startPos)
      const pos = startPos
      const { nodeBefore } = view.state.doc.resolve(pos)

      if (!nodeBefore) {
        console.log('no node before...')
        return
      }

      // Do prosemirror transaction to move node up!
      const newPosition = pos - nodeBefore.nodeSize;
      const from = pos
      const to = pos + node.nodeSize
      console.log('newPosition: ', newPosition)
      console.log('from: ', from)
      console.log('to: ', to)

      const transactionMoveNodeUp = view.state.tr
        .replace(from, to)
        .insert(newPosition, node)

      console.log(transactionMoveNodeUp)

      view.dispatch(transactionMoveNodeUp)
    },
    handleControlHitDown ({ node, view, startPos }) {
      // TODO this whole thing is broken and I dont know why...
      // console.log(`handleControlHitDown`, node, view, startPos)
      // const pos = startPos

      // const resolve = view.state.doc.resolve(pos)
      // console.log('resolve: ', resolve)

      // // console.log(resolve.)

      // if (!resolve.nodeAfter) {
      //   console.log('no node after...')
      //   return
      // }
      // // Do prosemirror transaction to move node up!
      // console.log('nodeAfter node size: ', resolve.nodeAfter.nodeSize)
      // const newPosition = pos + resolve.nodeAfter.nodeSize;

      // if (newPosition === view.docView.posAtEnd) {
      //   console.log('looks like already at end...')
      //   return
      // }

      // const from = pos
      // const to = pos + node.nodeSize
      // console.log('newPosition: ', newPosition)
      // console.log('from: ', from)
      // console.log('to: ', to)

      // const transactionMoveNodeDown = view.state.tr
      //   .insert(newPosition, node)
      //   .replace(from, to)

      // console.log(transactionMoveNodeDown)

      // view.dispatch(transactionMoveNodeDown)
    },
  },
  beforeDestroy() {
    this.editor.destroy()
  },
}
</script>
