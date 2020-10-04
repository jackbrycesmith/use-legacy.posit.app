<template>
  <portal to="modal">
    <BaseModal
      :is-visible.sync="isConfirming"
      :hint-description="descriptionText"
      :hint-title="titleText"
      :main-action-button-text="confirmButtonText"
      :main-action-button-colors="confirmButtonColors"
      :show-bottom-button-group="true"
      :on-background-hit="handleCancelHit"
      :on-close-button-hit="handleCancelHit"
      :on-escape-key-hit="handleCancelHit"
      :on-cancel-action-button-hit="handleCancelHit"
      :on-main-action-button-hit="handleConfirmedHit"
      :hint-icon-background-color="hintIconBgColor"
    >

    <template #hint-icon>
      <slot name="confirm-hint-icon">
        <IconHeroiconsMediumExclamation class="h-6 w-6 text-red-600" />
      </slot>
    </template>

    </BaseModal>
  </portal>
</template>

<script>
import BaseModal from '@/Modals/BaseModal'
import IconHeroiconsMediumExclamation from '@/Icons/IconHeroiconsMediumExclamation'

export default {
  props: {
    titleText: { type: String, default: 'Confirm' },
    descriptionText: { type: String, default: 'Are you sure you want to do this action?' },
    confirmButtonText: { type: String, default: 'Okay' },
    confirmButtonColors: { type: String, default: 'border border-transparent bg-red-400 text-white hover:bg-red-300 focus:outline-none focus:border-red-500 focus:shadow-outline-red' },
    hintIconBgColor: { type: String, default: 'bg-red-100' }
  },
  components: {
    BaseModal,
    IconHeroiconsMediumExclamation
  },
  data () {
    return {
      isConfirming: false
    }
  },
  methods: {
    show () {
      if (!this.isConfirming) {
        this.isConfirming = true
      }
    },
    handleCancelHit () {
      this.isConfirming = false
      this.$emit('cancelled')
    },
    handleConfirmedHit () {
      this.isConfirming = false
      this.$emit('confirmed')
    }
  }
}
</script>
