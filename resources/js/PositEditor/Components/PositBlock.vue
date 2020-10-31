<template>
  <component
    ref="positblock"
    :is="positComponent"
    :node="node"
    :view="view"
    :class="blockClass"
    :expanded="expanded"
    class="relative posit-block"
    @focus="handlePositBlockFocus">
    <!-- Would be nice for this to be the slot for a dynamic component based on the type.., but not sure if it will work... -->
    <template #controls>
      <button @click="handleControlHitUp" :contenteditable="false" class="absolute top-0 -mt-13" style="left: 50%; right: 50%;">
        ⬆️
      </button>

      <button @click="handleControlHitDown" :contenteditable="false" class="absolute bottom-0 -mb-13" style="left: 50%; right: 50%;">
        ⬇️
      </button>


      <!-- TODO template v-if="hasFocus" but it doesnt emit the event -->
      <button @click="handleAddBlockAbove" :contenteditable="false" class="absolute top-0 -mb-13 bg-white" style="left: 50%; right: 50%;">
        ➕
      </button>

      <button @click="handleDeleteBlock" :contenteditable="false" class="absolute top-0 -mb-13 bg-white" style="right: 40%;">
        ❌
      </button>

      <button @click="handleAddBlockBelow" :contenteditable="false" class="absolute bottom-0 -mb-2 bg-white" style="left: 50%; right: 50%;">
        ➕
      </button>

    </template>

    <template #collapsed-content="{ isExpanded }">
      <div>
        <h2 v-show="!isExpanded" class="text-2xl font-bold text-center truncate" style="white-space: nowrap;" :contenteditable="false">{{ blockTitle }}</h2>
      </div>
    </template>

    <template #content="{ isExpanded }">
      <div
        ref="content"
        class="prose"
        :contenteditable="view.editable.toString()"/>
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
  props: ['node', 'updateAttrs', 'view', 'getPos', 'selected', 'decorations'],
  computed: {
    positComponent () {
      return PositCardPanel
    },
    blockClass () {
      return 'max-w-7xl mx-auto px-4 sm:px-6 lg:px-8'
    },
    blockTitle () {
      if (this.node.firstChild?.type?.name === 'heading') {
        return this.node.firstChild.textContent
      }

      return this.node.textContent
    },
    expanded: {
      get() {
        return this.node.attrs.expanded
      },
      set(expanded) {
        this.updateAttrs({
          expanded,
        })
      },
    },
  },
  data () {
    return {
      hasFocus: false
    }
  },
  methods: {
    collapsedBlockHeaderContent () {
      console.log(this.node)
      return 'this.$refs.content?.innerText'
    },
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
    handlePositBlockFocus () {
      console.log('handlePositBlockFocus')
    },
    focusChanged (event) {
      if (! (event.target === this.$refs.positblock)) {
        console.log('el is not component...')
        return
      }
      console.log('vue component focus change: ', event)

      // do something with the element.
    },
    handleDecorationsChanged (decorations) {
      // Combined with the focus plugin, we're detecting whether the this component has foucs...
      if (decorations.length === 0) {
        // next tick maybe?
        this.hasFocus = false
        return
      }

      for (let decoration of decorations) {
        const focusClass = decoration?.type?.attrs?.class
        if (focusClass == 'has-focus') {
          this.hasFocus = true
          break
        }
      }
    },
    handleAddBlockAbove () {
      this.$parent.$emit('handleAddBlockAbove', {
        node: this.node,
        view: this.view,
        startPos: this.getPos()
      })
    },
    handleAddBlockBelow () {
      this.$parent.$emit('handleAddBlockBelow', {
        node: this.node,
        view: this.view,
        startPos: this.getPos()
      })
    },
    handleDeleteBlock () {
      this.$parent.$emit('handleDeleteBlock', {
        node: this.node,
        view: this.view,
        startPos: this.getPos()
      })
    },
    handleToggleExpand () {
      this.expanded = !this.expanded
    }
  },
  watch: {
    selected: {
      immediate: true,
      handler (value) {
        console.log('posit block selected: ', value)
      }
    },
    hasFocus: {
      immediate: true,
      handler (value) {
        console.log('hasFocus: ', value)
      }
    },
    decorations: {
      immediate: true,
      deep: true,
      handler (value) {
        this.handleDecorationsChanged(value)
      }
    },
  }
}
</script>
