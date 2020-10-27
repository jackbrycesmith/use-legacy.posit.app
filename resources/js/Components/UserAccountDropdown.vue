<template>
  <div class="relative">
    <div>
      <button ref="menuTriggerButton" @click="handleAvatarClick" class="max-w-xs flex items-center text-sm rounded-full focus:outline-none focus:bg-primary-yellow-200 lg:p-2 lg:rounded-md lg:hover:bg-primary-yellow-200" aria-label="User menu" aria-haspopup="true">

        <img v-if="hasProfilePhoto" :src="userProfilePhotoUrl" alt="User profile image" class="rounded-full h-8 w-8 object-cover">

        <div v-else class="h-8 w-8 rounded-full bg-primary-yellow-400 text-gray-900 font-semibold flex items-center justify-center">
          {{ userAvatarInitial }}
        </div>

        <p class="hidden ml-3 text-cool-gray-700 text-sm leading-5 font-medium lg:block">
          {{ userEmail }}
        </p>
        <svg class="hidden flex-shrink-0 ml-1 h-5 w-5 text-cool-gray-400 lg:block" viewBox="0 0 20 20" fill="currentColor">
          <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
        </svg>
      </button>
    </div>
    <!--
      Profile dropdown panel, show/hide based on dropdown state.

      Entering: "transition ease-out duration-100"
        From: "transform opacity-0 scale-95"
        To: "transform opacity-100 scale-100"
      Leaving: "transition ease-in duration-75"
        From: "transform opacity-100 scale-100"
        To: "transform opacity-0 scale-95"
    -->
    <transition
      enter-active-class="transition ease-out duration-100"
      enter-class="transform opacity-0 scale-95"
      enter-to-class="transform opacity-100 scale-100"
      leave-active-class="transition ease-in duration-75"
      leave-class="transform opacity-100 scale-100"
      leave-to-class="transform opacity-0 scale-95">
      <div
        v-click-outside="vcoConfig"
        v-if="isOpen"
        class="origin-top-right absolute right-0 mt-2 w-48 rounded-md shadow-lg">
        <div class="py-1 rounded-md bg-white shadow-xs" role="menu" aria-orientation="vertical" aria-labelledby="user-menu">
          <inertia-link @click="handleDropdownItemClick" :href="$route('profile.show')" class="block px-4 py-2 text-sm text-cool-gray-700 hover:bg-cool-gray-100 transition ease-in-out duration-150" role="menuitem">User Settings</inertia-link>
          <inertia-link :href="$route('logout')" method="post" class="block px-4 py-2 text-sm text-cool-gray-700 hover:bg-cool-gray-100 transition ease-in-out duration-150" role="menuitem">Logout</inertia-link>
        </div>
      </div>
    </transition>

  </div>
</template>

<script>
import { trim, isEmpty } from 'lodash-es'
import { initials } from '@/utils/strings'

export default {
  data () {
    return {
      isOpen: false,
      vcoConfig: {
        handler: this.onClickOutside,
        middleware: this.determineWhetherClickOutside,
        events: ['dblclick', 'click'],
        // Note: The default value is true, but in case you want to activate / deactivate
        //       this directive dynamically use this attribute.
        isActive: true
      }
    }
  },
  computed: {
    userEmail () {
      // TODO this should ideally be part of some global thing that I can import...
      return this.$page.props.user?.email
    },
    userProfilePhotoUrl () {
      return this.$page.props.user?.profile_photo_url
    },
    hasProfilePhoto () {
      return !isEmpty(this.userProfilePhotoUrl)
    },
    userAvatarInitial () {
      const name = trim(this.$page.props.user.name)

      if (name && name.length > 0) {
        return initials(name)
      }

      return initials(this.userEmail)
    }
  },
  methods: {
    handleAvatarClick () {
      this.isOpen = !this.isOpen
    },
    handleDropdownItemClick () {
      this.isOpen = false
    },
    onClickOutside (event) {
      if (this.isOpen) {
        this.isOpen = false
      }
    },
    determineWhetherClickOutside (event) {
      return event.target.parentElement !== this.$refs.menuTriggerButton
    }
  }
}
</script>
