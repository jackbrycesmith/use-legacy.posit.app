<template>
  <form @submit.prevent="handleFormSubmit">
    <slot name="button" v-bind="{ form }">
      <button
        type="submit"
        :disabled="form.processing"
        :class="[buttonClass, form.processing ? 'cursor-wait' : '']">
        <slot name="button-inner" v-bind="{ form }" />
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
    requestOptions: {
      type: Object,
      default: () => {}
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
      form: this.$inertia.form({}, { bag: this.errorBag }),
      hasJustRequestedConfirm: false
    }
  },
  methods: {
    submitForm () {
      this.form.submit(this.requestMethod, this.route, this.requestOptions)
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
