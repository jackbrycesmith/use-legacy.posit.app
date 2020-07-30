<template>
  <form @submit.prevent="handleFormSubmit">
    <slot name="contents" v-bind="{ isSubmitting }"/>
  </form>
</template>

<script>
export default {
  props: {
    onFormSubmit: { type: Function },
    retrieveFormPayload: { type: Function, required: true },
    requestUrl: { type: String },
    requestType: {
      type: String,
      default: 'inertia',
      validator (val) { return ['inertia', 'ajax'].includes(val) }
    },
    requestMethod: {
      type: String,
      default: 'post',
      validator (val) { return ['post', 'put', 'patch', 'delete'].includes(val) }
    },
  },
  data () {
    return {
      // Some kind of state machine?
      isSubmitting: false,
    }
  },
  computed: {
    handleFormSubmit () {
      if (typeof this.onFormSubmit === 'function') {
        return this.onFormSubmit.bind(this)
      }

      return this.defaultHandleFormSubmit
    }
  },
  methods: {
    defaultHandleFormSubmit () {
      if (this.isSubmitting) {
        return
      }
      this.isSubmitting = true

      if (this.requestType === 'inertia') {
        this.$inertia[this.requestMethod](
          this.requestUrl, this.retrieveFormPayload()
        )
      }

      // TODO idea; something like pass the url, type, and some kind of transformer to get the payload data
      // then prop to determine if is inertia, or ajax request...
    }
  }
}
</script>
