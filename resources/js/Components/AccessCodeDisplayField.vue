<template>
  <!-- TODO label form accessibility -->
  <div class="space-y-1">
    <label :class="labelClass">
      {{ label }}
    </label>

    <div class="mt-1 flex rounded-md shadow-sm">
      <div class="relative flex-grow focus-within:z-10">
        <input
          v-model="code"
          :type="isTypePassword ? `password` : `text`"
          class="form-input block w-full rounded-none rounded-l-md transition ease-in-out duration-150 sm:text-sm sm:leading-5 font-mono"
          disabled>
      </div>
      <button ref="copyAccessCodeButton" @click.prevent="handleCopyAccessCodeHit" class="-ml-px relative inline-flex items-center px-4 py-2 border border-gray-300 text-sm leading-5 font-medium rounded-r-md text-gray-700 bg-gray-50 hover:text-gray-500 hover:bg-white focus:outline-none focus:shadow-outline-yellow focus:border-primary-yellow-300 active:bg-gray-100 active:text-gray-700 transition ease-in-out duration-150">

        <SuccessFlashSwitcher
          ref="copyAccessCodeIcon"
          :to-blur-after-success-el="$refs['copyAccessCodeButton']">

          <template #normal>
            <IconHeroiconsSmallExternalLink class="h-5 w-5 text-gray-400" />
          </template>

          <template #success>
            <IconHeroiconsSmallCheck class="h-5 w-5 text-primary-yellow-400" />
          </template>
        </SuccessFlashSwitcher>


        <span class="ml-2">Copy</span>

      </button>
    </div>
  </div>
</template>

<script>
import IconHeroiconsSmallExternalLink from '@/Icons/IconHeroiconsSmallExternalLink'
import IconHeroiconsSmallCheck from '@/Icons/IconHeroiconsSmallCheck'
import SuccessFlashSwitcher from '@/Components/SuccessFlashSwitcher'
import copy from 'clipboard-copy'

export default {
  name: 'AccessCodeDisplayField',
  components: {
    IconHeroiconsSmallExternalLink,
    IconHeroiconsSmallCheck,
    SuccessFlashSwitcher
  },
  props: {
    label: {
      type: String,
      default: 'Access Code'
    },
    labelClass: {
      type: String,
      default: 'block text-sm font-medium leading-5 text-gray-600 truncate'
    },
    code: {
      type: String
    },
    isTypePassword: {
      type: Boolean,
      default: true
    }
  },
  data () {
    return {

    }
  },
  methods: {
    async handleCopyAccessCodeHit () {
      await copy(this.code)
      this.$refs.copyAccessCodeIcon.success()
    }
  }
}
</script>
