<template>
  <BaseForm
    :on-form-submit="handleFormSubmit">
    <template #contents>

      <!-- Errors -->
      <ul v-if="hasErrors" class="text-sm text-red-600 mb-1">
        <li v-for="error in form.errors()">
          {{ error }}
        </li>
      </ul>

      <!-- Form elements -->
      <div class="rounded-md shadow-sm">
        <div>
          <input
            v-model="form.email"
            aria-label="Email address"
            name="email"
            type="email"
            :disabled="form.processing"
            autocomplete="username"
            class="appearance-none rounded-none relative block w-full px-3 py-2 border border-gray-300 placeholder-gray-500 text-gray-900 rounded-t-md focus:outline-none focus:shadow-outline-blue focus:border-blue-300 focus:z-10 sm:text-sm sm:leading-5"
            placeholder="Email address"
            required>
        </div>
        <div class="-mt-px">
          <input
            v-model="form.password"
            aria-label="Password"
            name="current-password"
            type="password"
            :minlength="minPasswordLength"
            :disabled="form.processing"
            autocomplete="current-password"
            class="appearance-none rounded-none relative block w-full px-3 py-2 border border-gray-300 placeholder-gray-500 text-gray-900 rounded-b-md focus:outline-none focus:shadow-outline-blue focus:border-blue-300 focus:z-10 sm:text-sm sm:leading-5"
            placeholder="Password"
            required>
        </div>
      </div>

      <!-- Submit button -->
      <div class="mt-6">
        <button
          type="submit"
          :disabled="form.processing"
          :class="{ 'cursor-not-allowed': form.processing }"
          class="group relative w-full flex justify-center py-2 px-4 border border-transparent text-sm leading-5 font-medium rounded-md text-gray-800 bg-yellow-300 hover:bg-yellow-200 focus:outline-none focus:border-yellow-400 focus:shadow-outline-yellow active:bg-yellow-400 transition duration-150 ease-in-out">
          <span v-if="form.processing" class="absolute left-0 inset-y-0 flex items-center pl-3">
            <svg class="animate-spin h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
              </svg>
          </span>

          Log in
          <span class="absolute right-0 inset-y-0 flex items-center pr-3">
            <svg class="h-5 w-5 text-yellow-50 group-hover:text-yellow-100 transition ease-in-out duration-150" fill="currentColor" viewBox="0 0 20 20">
              <path fill-rule="evenodd" d="M5 9V7a5 5 0 0110 0v2a2 2 0 012 2v5a2 2 0 01-2 2H5a2 2 0 01-2-2v-5a2 2 0 012-2zm8-2v2H7V7a3 3 0 016 0z" clip-rule="evenodd" />
            </svg>
          </span>
        </button>
      </div>

    </template>
  </BaseForm>
</template>

<script>
import BaseForm from '@/Forms/BaseForm'

export default {
  components: {
    BaseForm
  },
  props: {
    minPasswordLength: { type: Number, default: 8 },
  },
  data () {
    return {
      form: this.$inertia.form({
        email: '',
        password: '',
        remember: true
      }, {
        bag: 'default',
        resetOnSuccess: true,
      }),
    }
  },
  computed: {
    hasErrors () {
      return this.form.errors().length > 0
    },
    route () {
      return this.$route('login')
    }
  },
  methods: {
    async handleFormSubmit () {
      await this.form.post(this.route)
    }
  }
}
</script>
