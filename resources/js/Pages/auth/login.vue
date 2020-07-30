<template>
  <div class="min-h-screen flex items-center justify-center bg-yellow-50 py-12 px-4 sm:px-6 lg:px-8">
    <div class="max-w-md w-full">
      <div>
        <img class="mx-auto h-12 w-auto" src="/posit-icon.png" alt="Posit.app logo">
        <h2 class="mt-6 text-center text-3xl leading-9 font-extrabold text-gray-900">
          Log in to Posit.app
        </h2>
        <p class="mt-2 text-center text-sm leading-5 text-gray-600">
          Or
          <inertia-link :href="$route('register')" class="font-medium text-yellow-400 hover:text-yellow-300 focus:outline-none focus:underline transition ease-in-out duration-150">
            create your free account
          </inertia-link>
        </p>
      </div>

      <NewLoginForm class="mt-8"/>

    </div>
  </div>

</template>
<script>
import app from '@/layouts/app'
import NewLoginForm from '@/Forms/NewLoginForm'

export default {
  components: { NewLoginForm },
  metaInfo: () => ({
    htmlAttrs: {
      class: ['h-full', 'bg-yellow-50']
      // TODO this removes existing class attributes on html... so maybe remove from app.blade.php & then follow the rule of allowing to ovveride bodyAttrs for classes?
    }
  }),
}
</script>
