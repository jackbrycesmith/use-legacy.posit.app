<template>
  <fragment>
    <ProposalOpeningSection
      class="mb-36"
      :proposal.sync="proposal__"
      @edit-title-done="handleEditTitleDone"
    />

      <div
        class="absolute h-full left-0 top-0">
        <div class="fixed h-screen">
          <ProposalBackHome
            class="absolute top-5 left-5"
          />
        </div>
      </div>

    <editor-content
      :editor="editor"
      @handleControlHitUp="handleControlHitUp"
      @handleControlHitDown="handleControlHitDown"
      @handleAddBlockAbove="handleAddBlockAbove"
      @handleAddBlockBelow="handleAddBlockBelow"
      @handleDeleteBlock="handleDeleteBlock"
    />

    <ProposalSlideOver
      ref="proposalSlideOver"
      :proposal="proposal__"
      @updatePressed="handleUpdatePressed"/>
    <FirstWelcomeModal ref="firstWelcomeModal"/>
    <LoginModal ref="loginModal"/>
  </fragment>
</template>

<script>
import ProposalSlideOver from '@/SlideOvers/ProposalSlideOver'
import FirstWelcomeModal from '@/Modals/FirstWelcomeModal'
import ProposalOpeningSection from '@/Components/ProposalOpeningSection'
import LoginModal from '@/Modals/LoginModal'
import { Editor, EditorContent, EditorFloatingMenu } from 'tiptap'
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
import Proposal from '@/models/Proposal'
import ProposalBackHome from '@/Components/ProposalBackHome'

export default {
  components: { ProposalSlideOver, FirstWelcomeModal, LoginModal, EditorContent, EditorFloatingMenu, ProposalOpeningSection, ProposalBackHome },
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
      proposal__: Proposal.make(),
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
        this.proposal__ = Proposal.make(value)
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
          this.proposal__.content?.content
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
      console.log(`handleControlHitDown`, node, view, startPos)
      const pos = startPos

      const childAfter = view.state.doc.childAfter(startPos + node.nodeSize)
      console.log('childAfter: ', childAfter)

      if (childAfter.node == null) {
        console.log('cannot move down because its the last one/no node after...')
        return
      }

      const transactionMoveNodeDown = view.state.tr
        .replace(pos, pos + node.nodeSize)
        .insert(pos + childAfter.node.nodeSize, node)

      view.dispatch(transactionMoveNodeDown)
    },
    handleAddBlockAbove ({ node, view, startPos }) {
      console.log('handleAddBlockAbove')
      const transaction = view.state.tr.insert(startPos, this.editor.schema.node("posit_block", null, [this.editor.schema.node("paragraph")]))
      view.dispatch(transaction)
    },
    handleAddBlockBelow ({ node, view, startPos }) {
      console.log('handleAddBlockBelow: ', node, view, startPos)
      const transaction = view.state.tr.insert(startPos + node.nodeSize, this.editor.schema.node("posit_block", null, [this.editor.schema.node("paragraph")]))
      view.dispatch(transaction)
    },
    handleDeleteBlock ({ node, view, startPos }) {
      console.log('handleDeleteBlock: ', node, view, startPos)
      const transaction = view.state.tr.replace(startPos, startPos + node.nodeSize)
      view.dispatch(transaction)
    },
    handleEditTitleDone (value) {

    }
  },
  beforeDestroy() {
    this.editor.destroy()
  },
}
</script>
