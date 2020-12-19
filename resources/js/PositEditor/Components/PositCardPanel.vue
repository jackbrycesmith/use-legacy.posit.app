<template>
  <div @focusin="hasFocusIn = true" @focusout="hasFocusIn = false">
    <SmoothReflow
      :options="{ property: 'height', transition: 'height .25s ease-in-out' }"
      :contenteditable="isExpanded && isEditable"
      tag="div"
      class="bg-white shadow rounded-lg mx-auto"
      style="max-width: 70ch;">
      <div :contenteditable="isExpanded && isEditable" class="px-4 py-5 sm:p-6 focus:outline-none relative" style="min-height: 100px;">

        <div v-show="isExpanded">
          <slot name="content" v-bind="{ isExpanded }"/>
        </div>

        <!-- TODO figure out why this is taking up some vertical space... -->
        <div class="absolute left-0 right-0 px-4">
          <slot v-if="!isExpanded" name="collapsed-content" v-bind="{ isExpanded }" />
        </div>

        <slot name="controls" />
        <slot name="overlay" />
      </div>
    </SmoothReflow>
  </div>
</template>

<script>
import SmoothReflow from '@/Components/SmoothReflow'

export default {
  components: { SmoothReflow },
  // there are some props available
  // `node` is a Prosemirror Node Object
  // `updateAttrs` is a function to update attributes defined in `schema`
  // `view` is the ProseMirror view instance
  // `options` is an array of your extension options
  // `selected` is a boolean which is true when selected
  // `editor` is a reference to the TipTap editor instance
  // `getPos` is a function to retrieve the start position of the node
  // `decorations` is an array of decorations around the node
  props: ['node', 'isEditable', 'expanded'],
  computed: {
    isExpanded () { return this.expanded }
  },
  data () {
    return {
      hasFocusIn: false
    }
  },
  // Could probably pass these down...
  watch: {
    expanded: {
      immediate: true,
      handler (value) {

      }
    }
  },
  methods: {
    handleFocusIn () {
      console.log('handleFocusIn')
    }
  }
}
</script>
