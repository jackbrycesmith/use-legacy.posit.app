<template>
  <fragment>
    <PositOpeningSection
      class="mb-36"
      :editable="editorMachineContext.canEdit"
      :proposal.sync="proposal__"
      @live-edit-name="handleLiveEditedName"
      @edit-title-done="handleEditTitleDone"/>

    <PositContentSection
      ref="content"
      :editable="editorMachineContext.canEdit"
      @update="handleContentUpdate"/>

    <PositClosingSection
      class="mt-36"
      :proposal="proposal__" />

    <PositBackHome
      class="fixed top-5 left-5"/>

    <PositSlideOver
      ref="positSlideOver"
      :posit-editor-machine-state="editorMachineCurrentState"
      :proposal.sync="proposal__"/>

    <!-- Modals -->
    <FirstWelcomeModal ref="firstWelcomeModal"/>
    <LoginModal ref="loginModal"/>
    <portal-target name="proposal-view-portal" />
  </fragment>
</template>

<script>
import { interpret } from 'xstate'
import { PositSlideOver } from '@/SlideOvers/PositSlideOver'
import FirstWelcomeModal from '@/Modals/FirstWelcomeModal'
import PositOpeningSection from '@/Components/PositOpeningSection'
import PositContentSection from '@/Components/PositContentSection'
import PositClosingSection from '@/Components/PositClosingSection'
import LoginModal from '@/Modals/LoginModal'
import Posit from '@/models/Posit'
import PositBackHome from '@/Components/PositBackHome'
import { proposalEditorMachine } from '@/machines/proposalEditorMachine'
import { debounce } from 'lodash-es'

export default {
  components: {
    PositSlideOver,
    FirstWelcomeModal,
    LoginModal,
    PositOpeningSection,
    PositContentSection,
    PositClosingSection,
    PositBackHome
  },
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
  created () {
    this.editorMachineService
      .onTransition(state => {
        this.editorMachineCurrentState = state
        this.editorMachineContext = state.context
      })
      .start()

    this.setupInitialMachineContext()
  },
  data () {
    const machine = proposalEditorMachine.withConfig({})

    return {
      editorMachineService: interpret(machine, { devTools: true }),
      editorMachineCurrentState: machine.initialState,
      editorMachineContext: machine.context,
      proposal__: Posit.make(),
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
      this.$refs.positSlideOver.show()
    }, 1200)
  },
  watch: {
    proposal: {
      immediate: true,
      handler (value) {
        this.proposal__ = Posit.make(value)

        this.$nextTick(() => {
          this.$refs.content.editor.setContent(
            this.proposal__.content?.content
          )
        })
      }
    },
    'proposal__.is_editable': {
      handler (value) {
        this.handleEditableEventSend(value)
      }
    },
  },
  methods: {
    setupInitialMachineContext () {
      this.handleEditableEventSend(this.proposal__.is_editable)
    },
    handleEditableEventSend (value) {
      const event = value ? 'CAN_EDIT' : 'CANNOT_EDIT'
      this.editorMachineService.send(event)
    },
    handleContentUpdate ({ state, getHTML, getJSON, transaction }) {
      if (!this.editorMachineContext.canEdit) return

      this.updateContentOnServer({ payload: getJSON(), vm: this })
    },
    updateContentOnServer: debounce(async ({ payload, vm }) => {
      const response = await vm.$http.put(
        vm.$route('use.submit.upsert-posit-content', { proposal: vm.proposalUuid }),
        payload
      )
      console.log('updateContentOnServer response: ', response)
    }, 1000),
    handleEditTitleDone (name) {
      if (!this.editorMachineContext.canEdit) return

      this.updateNameOnServer({ payload: { name }, vm: this })
    },
    handleLiveEditedName (name) {
      if (!this.editorMachineContext.canEdit) return

      this.updateNameOnServer({ payload: { name }, vm: this })
    },
    updateNameOnServer: debounce(async ({ payload, vm }) => {
      const response = await vm.$http.put(
        vm.$route('use.submit.upsert-posit-name', { proposal: vm.proposalUuid }),
        payload
      )
    }, 1000)
  },
}
</script>
