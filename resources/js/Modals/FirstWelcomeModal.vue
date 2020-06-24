<template>
  <BaseModal
    :is-visible.sync="isVisible"
    :hint-description="hintDescription"
    :hint-title="hintTitle"
    :show-bottom-button-group="false"
    hint-icon-background-color="bg-orange-50"
    @opened="handleWhenOpened"
  >
    <template #hint-icon>
      <svg class="h-6 w-6 text-orange-400" fill="none" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" viewBox="0 0 24 24" stroke="currentColor"><path d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
    </template>

  </BaseModal>
</template>

<script>
import BaseModal from '@/Modals/BaseModal'

export default {
  components: { BaseModal },
  data: () => ({
    isVisible: false
  }),
  computed: {
    hintTitle () {
      return 'Welcome to Posit!'
    },
    hintDescription () {
      return `You can see what it's like to create a proposal right away! <br><br> <span class="italic">Please note that <span class="underline">you'll need to sign in to have your changes saved</span> & use the rest of the feature set.</span>`
    },
  },
  methods: {
    show () {
      this.isVisible = true
      return this
    },
    handleBackgroundHit () {
      this.isVisible = false
    },
    handleCloseButtonHit () {
      this.isVisible = false
    },
    handleWhenOpened () {

    }
  }
}
</script>
