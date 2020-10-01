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
  </form>
</template>

<script>
export default {
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
    }
  },
  data () {
    return {
      form: this.$inertia.form({}, { bag: this.errorBag })
    }
  },
  methods: {
    handleFormSubmit () {
      this.form.submit(this.requestMethod, this.route, this.requestOptions)
    }
  }
}
</script>
