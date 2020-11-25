<template>
  <BaseModal
    :is-visible.sync="isVisible"
    :hint-description="hintDescription"
    :hint-title="hintTitle"
    :show-bottom-button-group="false"
    :on-background-hit="handleBackgroundHit"
    :on-close-button-hit="handleCloseButtonHit"
    :on-escape-key-hit="handleEscapeKeyHit"
    hint-icon-background-color="bg-gray-50"
    @opened="handleWhenOpened"
  >
    <template #hint-icon>
      <svg class="h-6 w-6 text-orange-300" fill="none" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" viewBox="0 0 24 24" stroke="currentColor"><path d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z"></path></svg>
    </template>

    <template #hint-body-section>
<!--       <LoginForm
        ref="loginForm"
        class="mt-3"
        @logging-in="canClose = false"
        @login-success="handleLoginSuccess"
        @login-error="canClose = true"
        /> -->
    </template>

  </BaseModal>
</template>

<script>
import BaseModal from '@/Modals/BaseModal'
// import LoginForm from '@/Forms/LoginForm'

export default {
  components: { BaseModal },
  data: () => ({
    isVisible: false,
    canClose: true
  }),
  computed: {
    hintTitle () {
      return 'Sign in to your Posit account'
    },
    hintDescription () {
      return ``
    },
  },
  methods: {
    show () {
      this.isVisible = true
      return this
    },
    handleBackgroundHit () {
      if (!this.canClose) return
      this.isVisible = false
    },
    handleCloseButtonHit () {
      if (!this.canClose) return
      this.isVisible = false
    },
    handleEscapeKeyHit () {
      if (!this.canClose) return
      this.isVisible = false
    },
    handleWhenOpened () {

    },
    handleLoginSuccess () {
      this.canClose = true
      // TODO: show login success notification??
      // TODO: if available, ask if they want to add touch id/face id for faster login?

      // TODO remove this timeout thing (unnecessary)
      setTimeout(() => {
        this.isVisible = false
      }, 400)
    }
  }
}
</script>
