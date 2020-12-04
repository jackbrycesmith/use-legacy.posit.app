<template>
  <fragment>
    <PositOpeningSection
      class="mb-36"
      :editable="editorMachineContext.canEdit"
      :posit.sync="posit__"
      @live-edit-name="handleLiveEditedName"
      @edit-title-done="handleEditTitleDone"/>

    <PositContentSection
      ref="content"
      :editable="editorMachineContext.canEdit"
      @update="handleContentUpdate"/>

    <PositClosingSection
      class="mt-36"
      :posit="posit__" />

    <PositBackHome
      class="fixed top-5 left-5"/>

    <PositSlideOver
      ref="positSlideOver"
      :posit-editor-machine-state="editorMachineCurrentState"
      :posit.sync="posit__"/>

    <!-- Modals -->
    <FirstWelcomeModal ref="firstWelcomeModal"/>
    <LoginModal ref="loginModal"/>
    <portal-target name="posit-view-portal" />
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
import { positEditorMachine } from '@/machines/positEditorMachine'
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
    posit: { type: Object }
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
    const machine = positEditorMachine.withConfig({})

    return {
      editorMachineService: interpret(machine, { devTools: true }),
      editorMachineCurrentState: machine.initialState,
      editorMachineContext: machine.context,
      posit__: Posit.make(),
    }
  },
  computed: {
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
    posit: {
      immediate: true,
      handler (value) {
        this.posit__ = Posit.make(value)

        this.$nextTick(() => {
          this.$refs.content.editor.setContent(
            this.posit__.content?.content
          )
        })
      }
    },
    'posit__.is_editable': {
      handler (value) {
        this.handleEditableEventSend(value)
      }
    },
  },
  methods: {
    setupInitialMachineContext () {
      this.handleEditableEventSend(this.posit__.is_editable)
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
        vm.$route('use.submit.upsert-posit-content', { posit: vm.posit__.uuid }),
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
        vm.$route('use.submit.upsert-posit-name', { posit: vm.posit__.uuid }),
        payload
      )
    }, 1000)
  },
}
</script>
