<template>
  <BaseForm
    :on-form-submit="handleFormSubmit">

    <template #contents>
      <div class="shadow overflow-hidden sm:rounded-md">
        <div class="px-4 py-5 bg-white sm:p-6">
          <div class="grid grid-cols-6 gap-6">

            <!-- Team Logo -->
            <div class="col-span-6 sm:col-span-4">
                <!-- Profile Photo File Input -->
                <input type="file" class="hidden"
                            ref="logo"
                            @change="updateLogoPreview">

                <jet-label for="logo" value="Logo" />

                <!-- Current Logo -->
                <div class="mt-2" v-show="! logoPreview">
                  <img v-if="teamLogoUrl" :src="teamLogoUrl" alt="Current Team Logo" class="rounded-full h-20 w-20 object-cover">

                  <div v-else class="h-20 w-20 rounded-full bg-primary-yellow-400 text-gray-900 font-semibold flex items-center justify-center text-2xl">
                    {{ teamInitials }}
                  </div>
                </div>

                <!-- New Logo Preview -->
                <div class="mt-2" v-show="logoPreview">
                    <span class="block rounded-full w-20 h-20"
                          :style="'background-size: cover; background-repeat: no-repeat; background-position: center center; background-image: url(\'' + logoPreview + '\');'">
                    </span>
                </div>

                <template v-if="permissions.canUpdateTeam">
                  <jet-secondary-button class="mt-2 mr-2" type="button" @click.native.prevent="selectNewPhoto">
                      Select A New Logo
                  </jet-secondary-button>

                  <jet-secondary-button type="button" class="mt-2" @click.native.prevent="deleteLogo" v-if="teamLogoUrl">
                      Remove Logo
                  </jet-secondary-button>

                  <jet-input-error :message="form.error('logo')" class="mt-2" />
                </template>

            </div>

            <!-- Team Name -->
            <div class="col-span-6 sm:col-span-4">
              <jet-label for="name" value="Team Name" />
              <jet-input id="name" type="text" class="mt-1 block w-full" v-model="form.name" :disabled="! permissions.canUpdateTeam" />
              <jet-input-error :message="form.error('name')" class="mt-2" />
            </div>

          </div>
        </div>

        <!-- Actions... -->
        <div
          v-if="permissions.canUpdateTeam"
          class="flex items-center justify-end px-4 py-3 bg-gray-50 text-right sm:px-6">

          <jet-action-message :on="form.recentlySuccessful" class="mr-3">
            Saved.
          </jet-action-message>

          <jet-button :class="{ 'opacity-25': form.processing }" :disabled="form.processing">
            Save
          </jet-button>

        </div>
      </div>
    </template>

  </BaseForm>
</template>

<script>
import BaseForm from '@/Forms/BaseForm'
import JetButton from '@/Jetstream/Button'
import JetInput from '@/Jetstream/Input'
import JetInputError from '@/Jetstream/InputError'
import JetLabel from '@/Jetstream/Label'
import JetActionMessage from '@/Jetstream/ActionMessage'
import JetSecondaryButton from '@/Jetstream/SecondaryButton'
import { initials } from '@/utils/strings'

export default {
  components: {
    BaseForm,
    JetInput,
    JetInputError,
    JetActionMessage,
    JetButton,
    JetLabel,
    JetSecondaryButton
  },
  props: ['team', 'permissions'],
  data () {
    return {
      form: this.$inertia.form({
        '_method': 'PUT',
        name: this.team.name,
        logo: null,
      }, {
        bag: 'updateTeamInfo',
        resetOnSuccess: false,
      }),
      logoPreview: null,
    }
  },
  computed: {
    route () {
      return this.$route('teams.update-info', { team: this.team.uuid })
    },
    deleteLogoRoute () {
      return this.$route('teams.delete-logo', { team: this.team.uuid })
    },
    teamInitials () {
      return initials(this.team.name)
    },
    teamLogoUrl () {
      return this.team.logo_url
    }
  },
  methods: {
    handleFormSubmit () {
      if (this.$refs.logo) {
          this.form.logo = this.$refs.logo.files[0]
      }

      this.form.post(this.route, {
        preserveScroll: false
      });
    },
    selectNewPhoto() {
        this.$refs.logo.click()
    },
    updateLogoPreview() {
        const reader = new FileReader();

        reader.onload = (e) => {
            this.logoPreview = e.target.result;
        };

        reader.readAsDataURL(this.$refs.logo.files[0]);
    },
    deleteLogo() {
        this.$inertia.delete(this.deleteLogoRoute, {
            preserveScroll: true,
        }).then(() => {
            this.logoPreview = null
        });
    },
  }
}
</script>
