<template>
  <div class="min-h-screen flex items-center justify-center bg-primary-yellow-50 py-12 px-4 sm:px-6 lg:px-8">
    <div class="max-w-md w-full">
      <div>
        <ApplicationLogo class="h-10 w-40 mx-auto" />
        <p class="mt-2 text-center text-sm leading-5 text-gray-600">
          <inertia-link :href="$route('login')" class="font-medium text-yellow-400 hover:text-yellow-300 focus:outline-none focus:underline transition ease-in-out duration-150">
            Access existing account &raquo;
          </inertia-link>
        </p>

        <h2 class="mt-6 text-center text-3xl leading-9 font-extrabold text-gray-900">
          Reset Password
        </h2>

        <p class="mt-2 text-center text-sm leading-5 text-gray-600">
          Forgot your password? Not to worry. Enter the account email & we'll send a link for you to choose a new password.
        </p>
      </div>

      <ForgotPasswordForm class="mt-8"/>

    </div>
  </div>
</template>

<script>
import ForgotPasswordForm from '@/Forms/ForgotPasswordForm'
import ApplicationLogo from '@/Jetstream/ApplicationLogo'

export default {
  components: { ApplicationLogo, ForgotPasswordForm },
  metaInfo: () => ({
    htmlAttrs: {
      class: ['h-full', 'bg-primary-yellow-50']
      // TODO this removes existing class attributes on html... so maybe remove from app.blade.php & then follow the rule of allowing to ovveride bodyAttrs for classes?
    }
  })
}
</script>
