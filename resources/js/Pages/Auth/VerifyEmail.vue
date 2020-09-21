<template>
  <div class="min-h-screen flex items-center justify-center bg-yellow-50 py-12 px-4 sm:px-6 lg:px-8">

    <div class="max-w-md w-full">
      <div>
        <img class="mx-auto h-12 w-auto" src="/posit-icon-2-grey-gold.svg" alt="Posit.app logo">
        <h2 class="mt-6 text-center text-3xl leading-9 font-extrabold text-gray-900">
          Verify your account
        </h2>
      </div>

      <div class="flex items-center justify-center mt-2">
        <UserAccountDropdown />
      </div>

      <IconSimpleIconsMinuteMailer
        class="h-10 w-10 mx-auto mt-2"
      />

      <p class="mt-10 text-center text-sm leading-5 text-gray-600">
        Please check your <strong>{{ $page.user.email }}</strong> inbox & follow the verification link.
      </p>

      <!-- TODO potentially have button to resend verification email -->

    </div>
  </div>
</template>
<script>
import UserAccountDropdown from '@/Components/UserAccountDropdown'
import IconSimpleIconsMinuteMailer from '@/Icons/IconSimpleIconsMinuteMailer'

export default {
  components: { IconSimpleIconsMinuteMailer, UserAccountDropdown },
  metaInfo: () => ({
    htmlAttrs: {
      class: ['h-full', 'bg-yellow-50']
      // TODO this removes existing class attributes on html... so maybe remove from app.blade.php & then follow the rule of allowing to ovveride bodyAttrs for classes?
    }
  })
}
</script>
