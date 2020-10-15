<template>
  <RenderlessTabPane
    :title="title"
    :icon="icon"
    @did-load="$emit('did-load')"
    #default="{ isSelected }">

    <div v-show="isSelected">
      <slot />
    </div>

  </RenderlessTabPane>
</template>

<script>
import RenderlessTabPane from '@/Components/Renderless/RenderlessTabPane'

export default {
  props: {
    title: { type: String, required: true },
    icon: {},
  },
  components: {
    RenderlessTabPane
  },
}
</script>
