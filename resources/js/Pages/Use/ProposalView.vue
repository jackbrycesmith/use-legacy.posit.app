<template>
  <fragment>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
      <!-- We've used 3xl here, but feel free to try other max-widths based on your needs -->
      <div class="max-w-3xl mx-auto">
        <!-- Content goes here -->

        <div class="bg-white overflow-hidden shadow rounded-lg mt-5">
          <div class="px-4 py-5 sm:p-6">
            <!-- Content goes here -->
            <editor-content :editor="editor" class="prose"/>
          </div>
        </div>

      </div>
    </div>

    <!-- TODO move to something app level, portal -->
    <span @click="toggleProposalSlideOver" class="cursor-pointer inline-flex items-center justify-center h-10 w-10 rounded-full overflow-hidden bg-indigo-50 absolute bottom-2 right-3 md:bottom-auto md:top-auto">
      <svg class="h-8 w-8 text-indigo-500 hover:text-indigo-400" fill="none" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" viewBox="0 0 24 24" stroke="currentColor"><path d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path><path d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
    </span>

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
    toggleProposalSlideOver () {
      const proposalSlideOver = this.$refs.proposalSlideOver
      if (!proposalSlideOver) { return }

      proposalSlideOver.isVisible ? proposalSlideOver.hide() : proposalSlideOver.show()
    },
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
