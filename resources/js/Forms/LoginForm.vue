<template>
  <form @submit.prevent="handleLogin">
    <input type="hidden" name="remember" value="true">
    <div class="rounded-md shadow-sm">
      <div>
        <input
          v-model="form.email"
          aria-label="Email address"
          name="email"
          type="email"
          :disabled="isSubmitting"
          autocomplete="username"
          required
          class="appearance-none rounded-none relative block w-full px-3 py-2 border border-gray-300 placeholder-gray-500 text-gray-900 rounded-t-md focus:outline-none focus:shadow-outline-blue focus:border-blue-300 focus:z-10 sm:text-sm sm:leading-5"
          placeholder="Email address"
        >
      </div>
      <div class="-mt-px">
        <input
          ref="password"
          v-model="form.password"
          aria-label="Password"
          name="current-password"
          type="password"
          :minlength="minPasswordLength"
          :disabled="isSubmitting"
          autocomplete="current-password"
          required
          class="appearance-none rounded-none relative block w-full px-3 py-2 border border-gray-300 placeholder-gray-500 text-gray-900 rounded-b-md focus:outline-none focus:shadow-outline-blue focus:border-blue-300 focus:z-10 sm:text-sm sm:leading-5"
          placeholder="Password"
        >
      </div>
    </div>

    <slot v-if="showBetweenInputsAndSubmit" name="between-inputs-and-submit">
      <div class="mt-6 flex items-center justify-between">
        <div class="flex items-center">
          <input id="remember_me" type="checkbox" class="form-checkbox h-4 w-4 text-red-400 transition duration-150 ease-in-out">
          <label for="remember_me" class="ml-2 block text-sm leading-5 text-gray-900">
            Remember me
          </label>
        </div>

        <div class="text-sm leading-5">
          <a href="#" class="font-medium text-red-400 hover:text-red-300 focus:outline-none focus:underline transition ease-in-out duration-150">
            Forgot your password?
          </a>
        </div>
      </div>
    </slot>

    <div class="mt-6">
      <button type="submit" :disabled="isSubmitting" class="group relative w-full flex justify-center py-2 px-4 border border-transparent text-sm leading-5 font-medium rounded-md text-white bg-orange-400 hover:bg-orange-300 focus:outline-none focus:border-orange-500 focus:shadow-outline-orange active:bg-orange-500 transition duration-150 ease-in-out">
        <span class="absolute left-0 inset-y-0 flex items-center pl-3">
          <svg class="h-5 w-5 text-orange-300 group-hover:text-orange-200 transition ease-in-out duration-150" fill="currentColor" viewBox="0 0 20 20">
            <path fill-rule="evenodd" d="M5 9V7a5 5 0 0110 0v2a2 2 0 012 2v5a2 2 0 01-2 2H5a2 2 0 01-2-2v-5a2 2 0 012-2zm8-2v2H7V7a3 3 0 016 0z" clip-rule="evenodd" />
          </svg>
        </span>
        {{ signInButtonText }}
      </button>
    </div>
  </form>
</template>

<script>
export default {
  props: {
    showBetweenInputsAndSubmit: { type: Boolean, default: false },
    minPasswordLength: { type: Number, default: 8 },
    signInButtonText: { type: String, default: 'Sign in' }
  },
  data () {
    return {
      form: {
        email: '',
        password: ''
      },
      isSubmitting: false
    }
  },
  methods: {
    handleLogin () {
      // TODO input validation? (relying on basic html validation for now...)
      this.login(this.form.email, this.form.password)
    },
    async login (email, password) {
      try {
        this.isSubmitting = true
        this.$emit('logging-in')
        const res = await this.$http.post(
          this.$route('login'), { ...this.form }
        )
        console.log('res', res)
        this.$emit('login-success')
      } catch (e) {
        this.$nextTick(() => {
          setTimeout(() => {
            this.$refs.password?.focus()
          }, 50)
        })
        this.isSubmitting = false
        this.form.password = ''

        // TODO display error messages
        this.$emit('login-error')
        console.error(e)
      }
    }
  }
}
</script>
