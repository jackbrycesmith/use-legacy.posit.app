<template>
  <div class="flex items-center justify-between">
    <span class="flex-grow flex flex-col" id="toggleLabel">
      <span class="text-sm font-medium text-gray-900">Include pricing</span>
      <span class="text-sm leading-normal text-gray-500">
        Let the recipient pay when accepting.
      </span>
    </span>

    <ToggleButton
      :model-value.sync="posit.includes_pricing"
      :disabled="!editable"
      @changed="handleUpdatePositType"
      color="primary-yellow"/>
  </div>

</template>

<script>
import ToggleButton from '@/Components/TailwindUI/ToggleButton'

export default {
  name: 'PositTypeToggle',
  components: {
    ToggleButton
  },
  props: {
    posit: { type: Object },
    editable: {
      type: Boolean,
      default: true
    },
  },
  methods: {
    async handleUpdatePositType () {
      if (!this.editable) {
        return
      }

      try {
        await this.posit.updatePositType()
      } catch (e) {
        // TODO handle update project value error...
      }

    }
  }
}
</script>
