<template>
  <fragment>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
      <!-- We've used 3xl here, but feel free to try other max-widths based on your needs -->
      <div class="max-w-3xl mx-auto">
        <!-- Content goes here -->

        <div class="bg-white overflow-hidden shadow rounded-lg mt-5">
          <div class="px-4 py-5 sm:p-6">
            <!-- Content goes here -->
            <editor-content :editor="editor"/>
          </div>
        </div>

      </div>
    </div>

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

export default {
  components: { ProposalSlideOver, FirstWelcomeModal, LoginModal, EditorContent },
  props: {
    proposal: { type: Object }
  },
  data () {
    return {
      keepInBounds: true,
      editor: new Editor({
        editorProps: {
          attributes: {
            class: 'prose'
          }
        },
        extensions: [
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
          new Bold(),
          new Code(),
          new Italic(),
          new Strike(),
          new Underline(),
          new History(),
        ],
      })
    }
  },
  computed: {
    proposalUuid () {
      return this.proposal.data.uuid
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
        this.editor.setContent(value.data?.content?.content)
      }
    }
  },
  methods: {
    async handleUpdatePressed () {
      // TODO this is not production ready
      const response = this.$http.put(
        this.$route('use.submit.upsert-proposal-content', { proposal: this.proposalUuid }),
        this.editor.getJSON()
      )
      console.log(response)
    }
  },
  beforeDestroy() {
    this.editor.destroy()
  }
}
</script>
