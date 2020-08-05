<template>
  <component :is="positComponent" :node="node" :view="view" class="relative">
    <!-- Would be nice for this to be the slot for a dynamic component based on the type.., but not sure if it will work... -->
    <template #controls>
      <button @click="handleControlHitUp" :contenteditable="false" class="absolute top-0 -mt-13" style="left: 50%; right: 50%;">
        ⬆️
      </button>

      <button @click="handleControlHitDown" :contenteditable="false" class="absolute bottom-0 -mb-13" style="left: 50%; right: 50%;">
        ⬇️
      </button>
    </template>

    <template #content>
      <div ref="content" :contenteditable="view.editable.toString()"/>
    </template>
  </component>
</template>

<script>
import PositCardPanel from '@/PositEditor/Components/PositCardPanel'

export default {
  components: { PositCardPanel },
  // there are some props available
  // `node` is a Prosemirror Node Object
  // `updateAttrs` is a function to update attributes defined in `schema`
  // `view` is the ProseMirror view instance
  // `options` is an array of your extension options
  // `selected` is a boolean which is true when selected
  // `editor` is a reference to the TipTap editor instance
  // `getPos` is a function to retrieve the start position of the node
  // `decorations` is an array of decorations around the node
  props: ['node', 'updateAttrs', 'view', 'getPos'],
  computed: {
    positComponent () {
      return PositCardPanel
    },
    // type: {
    //   get() {
    //     return this.node.attrs.positType
    //   },
    //   set(type) {
    //     // we cannot update `type` itself because `this.node.attrs` is immutable
    //     this.updateAttrs({
    //       positType,
    //     })
    //   },
    // },
  },
  methods: {
    handleControlHitUp () {
      this.$parent.$emit('handleControlHitUp', {
        node: this.node,
        view: this.view,
        startPos: this.getPos()
      })
    },
    handleControlHitDown () {
      this.$parent.$emit('handleControlHitDown', {
        node: this.node,
        view: this.view,
        startPos: this.getPos()
      })
    },
  },
  watch: {
    // type: {
    //   immediate: true,
    //   handler (value) {
    //     console.log('PositBlock type: ', type)
    //   }
    // }
  }
}
</script>
