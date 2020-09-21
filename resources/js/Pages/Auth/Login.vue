<template>
  <div class="min-h-screen flex items-center justify-center bg-yellow-50 py-12 px-4 sm:px-6 lg:px-8 relative">
    <div class="max-w-md w-full">
      <div>
        <ApplicationLogo class="h-10 w-40 mx-auto" />

        <p class="mt-2 text-center text-sm leading-5 text-gray-600">
          <inertia-link :href="$route('register')" class="font-medium text-yellow-400 hover:text-yellow-300 focus:outline-none focus:underline transition ease-in-out duration-150">
            Create your free account now &raquo;
          </inertia-link>
        </p>
        <h2 class="mt-6 text-center text-3xl leading-9 font-extrabold text-gray-900">
          Log in to your account
        </h2>
      </div>

      <NewLoginForm class="mt-8"/>
      <p class="mt-2 text-center text-sm leading-5 text-gray-600 absolute bottom-4 right-0 left-0">
        <inertia-link :href="$route('password.request')" class="font-medium text-yellow-300 hover:text-yellow-400 focus:outline-none focus:underline transition ease-in-out duration-150">
          Forgot your password?
        </inertia-link>
      </p>

    </div>
  </div>

</template>

<script>
import NewLoginForm from '@/Forms/NewLoginForm'
import ApplicationLogo from '@/Jetstream/ApplicationLogo'

export default {
  components: { ApplicationLogo, NewLoginForm },
  metaInfo: () => ({
    htmlAttrs: {
      class: ['h-full', 'bg-yellow-50']
      // TODO this removes existing class attributes on html... so maybe remove from app.blade.php & then follow the rule of allowing to ovveride bodyAttrs for classes?
    }
  }),
}
</script>
