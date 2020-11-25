<template>
  <form @submit.prevent="handleFormSubmit">
    <slot name="button" v-bind="{ isFormProcessing }">
      <button
        type="submit"
        :disabled="isFormProcessing"
        :class="[buttonClass, isFormProcessing ? 'cursor-wait' : '']">
        <slot name="button-inner" v-bind="{ isFormProcessing }" />
      </button>
    </slot>

    <ConfirmModal
      ref="confirmModal"
      v-if="shouldConfirm && hasJustRequestedConfirm"
      :titleText="confirmModalTitle"
      :description-text="confirmModalDescription"
      :confirm-button-text="confirmModalButton"
      :confirm-button-colors="confirmModalButtonColors"
      :hint-icon-bg-color="confirmModalHintIconBgColor"
      @confirmed="submitForm"
      @cancelled="handleConfirmCancelled">

      <template #confirm-hint-icon>
        <slot name="confirm-modal-icon" />
      </template>

    </ConfirmModal>
  </form>
</template>

<script>
import ConfirmModal from '@/Modals/ConfirmModal'
import Http from '@/services/Http'
import { sleep } from '@/utils/sleep'

export default {
  components: {
    ConfirmModal
  },
  props: {
    route: { type: String, required: true },
    buttonClass: { type: String },
    requestMethod: {
      type: String,
      default: 'get',
      validator (val) { return ['get', 'post', 'put', 'patch', 'delete'].includes(val) }
    },
    requestData: {
      type: Object,
      default: () => {}
    },
    requestOptions: {
      type: Object,
      default: () => {}
    },
    minProcessingTimeMs: {
      type: Number,
      default: 0
    },
    errorBag: {
      type: String,
      default: 'default'
    },
    shouldConfirm: {
      type: Boolean,
      default: false
    },
    confirmModalTitle: {
      type: String,
      default: 'Confirm'
    },
    confirmModalDescription: {
      type: String,
      default: 'Are you sure you want to do this action?'
    },
    confirmModalButton: {
      type: String,
      default: 'Okay'
    },
    confirmModalButtonColors: {
      type: String,
      default: 'border border-transparent bg-red-400 text-white hover:bg-red-300 focus:outline-none focus:border-red-500 focus:shadow-outline-red'
    },
    confirmModalHintIconBgColor: {
      type: String,
      default: 'bg-red-100'
    }
  },
  data () {
    return {
      isFormProcessing: false,
      hasJustRequestedConfirm: false
    }
  },
  methods: {
    async submitForm () {
      this.isFormProcessing = true

      try {
        const timeBefore = performance.now()
        const response = await Http[this.requestMethod](this.route, this.requestData, this.requestOptions)

        const timeTakenMs = performance.now() - timeBefore
        if (timeTakenMs < this.minProcessingTimeMs) {
          await sleep(this.minProcessingTimeMs - timeTakenMs)
        }

        this.$emit('success', response)
      } catch (e) {
        this.$emit('error', e)
      } finally {
        this.isFormProcessing = false
      }
    },
    handleFormSubmit () {
      if (this.shouldConfirm) {
        this.hasJustRequestedConfirm = true
        this.$nextTick(() => {
          this.$refs.confirmModal.show()
        })
        return
      }

      this.submitForm()
    },
    handleConfirmCancelled () {
      //
    }
  }
}
</script>
