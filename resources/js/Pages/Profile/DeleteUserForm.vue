<template>
  <jet-action-section>
    <template #title>
      Delete Account
    </template>

    <template #description>
      Permanently delete your account.
    </template>

    <template #content>
      <div class="max-w-xl text-sm text-gray-600">
        Once your account is deleted, all of its resources and data will be permanently deleted. Before deleting your account, please download any data or information that you wish to retain.
      </div>

      <div class="mt-5">
        <jet-danger-button @click.native="confirmUserDeletion">
          Delete Account
        </jet-danger-button>
      </div>

      <!-- Delete Account Confirmation Modal -->
      <jet-dialog-modal :show="confirmingUserDeletion" @close="confirmingUserDeletion = false">

        <template #title>
          Delete Account
        </template>

        <template #content>
          Are you sure you want to delete your account? Once your account is deleted, all of its resources and data will be permanently deleted. Please enter your password to confirm you would like to permanently delete your account.
          <div class="mt-4">
            <jet-input type="password" class="mt-1 block w-3/4" placeholder="Password" ref="password" v-model="form.password" @keyup.enter.native="deleteUser" />
            <jet-input-error :message="form.error('password')" class="mt-2" />
          </div>
        </template>

        <template #footer>
          <jet-secondary-button @click.native="confirmingUserDeletion = false">
            Nevermind
          </jet-secondary-button>

          <jet-danger-button class="ml-2" @click.native="deleteUser" :class="{ 'opacity-25': form.processing }" :disabled="form.processing">
            Delete Account
          </jet-danger-button>
        </template>

      </jet-dialog-modal>
    </template>
  </jet-action-section>
</template>

<script>
import JetActionSection from '@/Jetstream/ActionSection'
import JetButton from '@/Jetstream/Button'
import JetDialogModal from '@/Jetstream/DialogModal'
import JetDangerButton from '@/Jetstream/DangerButton'
import JetInput from '@/Jetstream/Input'
import JetInputError from '@/Jetstream/InputError'
import JetSecondaryButton from '@/Jetstream/SecondaryButton'

export default {
  components: {
    JetActionSection,
    JetButton,
    JetDangerButton,
    JetDialogModal,
    JetInput,
    JetInputError,
    JetSecondaryButton,
  },

  data() {
    return {
      confirmingUserDeletion: false,
      deleting: false,

      form: this.$inertia.form({
        '_method': 'DELETE',
        password: '',
      }, {
        bag: 'deleteUser'
      })
    }
  },

  methods: {
    confirmUserDeletion() {
      this.form.password = '';

      this.confirmingUserDeletion = true;

      setTimeout(() => {
        this.$refs.password.focus()
      }, 250)
    },

    deleteUser() {
      this.form.post('/user', {
        preserveScroll: true
      }).then(response => {
        if (!this.form.hasErrors()) {
          this.confirmingUserDeletion = false;
        }
      })
    },
  },
}

</script>
