<template>
  <div class="py-10">
    <header>
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <h1 class="text-3xl font-bold leading-tight text-gray-900">
          User Settings
        </h1>
      </div>
    </header>
    <main>
      <div class="max-w-7xl mx-auto py-10 sm:px-6 lg:px-8">
        <update-profile-information-form :user="$page.props.user" />

        <jet-section-border />

        <update-password-form class="mt-10 sm:mt-0" />

        <div v-if="$page.props.jetstream.canManageTwoFactorAuthentication">
          <jet-section-border />
          <two-factor-authentication-form class="mt-10 sm:mt-0" />
        </div>

        <jet-section-border />

        <logout-other-browser-sessions-form :sessions="sessions" class="mt-10 sm:mt-0" />

        <jet-section-border />

        <delete-user-form class="mt-10 sm:mt-0" />
      </div>
    </main>
  </div>
</template>

<script>
import Dashboard from '@/Layouts/Dashboard'
import DeleteUserForm from './DeleteUserForm'
import JetSectionBorder from '@/Jetstream/SectionBorder'
import LogoutOtherBrowserSessionsForm from './LogoutOtherBrowserSessionsForm'
import TwoFactorAuthenticationForm from './TwoFactorAuthenticationForm'
import UpdatePasswordForm from './UpdatePasswordForm'
import UpdateProfileInformationForm from './UpdateProfileInformationForm'

export default {
  props: ['sessions'],
  layout: Dashboard,
  components: {
    DeleteUserForm,
    JetSectionBorder,
    LogoutOtherBrowserSessionsForm,
    TwoFactorAuthenticationForm,
    UpdatePasswordForm,
    UpdateProfileInformationForm,
  },
}
</script>
