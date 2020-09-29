<template>
  <BaseForm
    :on-form-submit="handleFormSubmit">

    <template #contents>
      <div class="shadow overflow-hidden sm:rounded-md">
        <div class="px-4 py-5 bg-white sm:p-6">
          <div class="grid grid-cols-6 gap-6">

            <!-- Team Logo -->
            <div class="col-span-6 sm:col-span-4">
              <!-- TODO -->
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

export default {
  components: {
    BaseForm,
    JetInput,
    JetInputError,
    JetActionMessage,
    JetButton,
    JetLabel
  },
  props: ['team', 'permissions'],
  data () {
    return {
      form: this.$inertia.form({
        name: this.team.name,
      }, {
        bag: 'updateTeamName',
        resetOnSuccess: false,
      })
    }
  },
  computed: {
    route () {
      return this.$route('teams.update-name', { team: this.team.uuid })
    }
  },
  methods: {
    handleFormSubmit () {
      this.form.put(this.route, {
        preserveScroll: false
      });
    }
  }
}
</script>
