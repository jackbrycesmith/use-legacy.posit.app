<template>
  <fragment>
    <ProposalOpeningSection
      class="mb-36"
      :proposal.sync="proposal__"
      @live-edit-name="handleLiveEditedName"
      @edit-title-done="handleEditTitleDone"/>

    <ProposalContentSection
      ref="content"
      :editable="editorMachineContext.canEdit"
      @update="handleContentUpdate"/>

    <ProposalClosingSection
      class="mt-36"
      :proposal="proposal__" />

    <ProposalBackHome
      class="fixed top-5 left-5"/>

    <ProposalSlideOver
      ref="proposalSlideOver"
      :proposal.sync="proposal__"
      @updatePressed="handleUpdatePressed"/>

    <!-- Modals -->
    <FirstWelcomeModal ref="firstWelcomeModal"/>
    <LoginModal ref="loginModal"/>
    <portal-target name="proposal-view-portal" />
  </fragment>
</template>

<script>
import { interpret } from 'xstate'
import { ProposalSlideOver } from '@/SlideOvers/ProposalSlideOver'
import FirstWelcomeModal from '@/Modals/FirstWelcomeModal'
import ProposalOpeningSection from '@/Components/ProposalOpeningSection'
import ProposalContentSection from '@/Components/ProposalContentSection'
import ProposalClosingSection from '@/Components/ProposalClosingSection'
import LoginModal from '@/Modals/LoginModal'
import Proposal from '@/models/Proposal'
import ProposalBackHome from '@/Components/ProposalBackHome'
import { proposalEditorMachine } from '@/machines/proposalEditorMachine'
import { debounce } from 'lodash-es'

export default {
  components: {
    ProposalSlideOver,
    FirstWelcomeModal,
    LoginModal,
    ProposalOpeningSection,
    ProposalContentSection,
    ProposalClosingSection,
    ProposalBackHome
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
      proposal__: Proposal.make(),
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
      this.$refs.proposalSlideOver.show()
    }, 1200)
  },
  watch: {
    proposal: {
      immediate: true,
      handler (value) {
        this.proposal__ = Proposal.make(value)

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
      this.updateContentOnServer({ payload: getJSON(), vm: this })
    },
    updateContentOnServer: debounce(async ({ payload, vm }) => {
      const response = await vm.$http.put(
        vm.$route('use.submit.upsert-proposal-content', { proposal: vm.proposalUuid }),
        payload
      )
      console.log('updateContentOnServer response: ', response)
    }, 1000),
    async handleUpdatePressed () {
      // TODO this is not production ready
      const response = this.$http.put(
        this.$route('use.submit.upsert-proposal-content', { proposal: this.proposalUuid }),
        this.$refs.content.editor.getJSON()
      )
      console.log(response)
    },
    handleEditTitleDone (name) {
      this.updateNameOnServer({ payload: { name }, vm: this })
    },
    handleLiveEditedName (name) {
      this.updateNameOnServer({ payload: { name }, vm: this })
    },
    updateNameOnServer: debounce(async ({ payload, vm }) => {
      const response = await vm.$http.put(
        vm.$route('use.submit.upsert-proposal-name', { proposal: vm.proposalUuid }),
        payload
      )
    }, 1000)
  },
}
</script>
