<template>
  <node-view-wrapper>
    <component
      ref="positblock"
      :is="positComponent"
      :node="node"
      :is-editable="isViewEditable"
      :class="blockClass"
      :expanded="expanded"
      class="relative posit-block">
        <!-- Would be nice for this to be the slot for a dynamic component based on the type.., but not sure if it will work... -->
        <template #controls>
          <!-- TODO template v-if="hasFocus" but it doesnt emit the event -->

          <template v-if="isViewEditable">

            <div class="absolute top-0 -mt-7 -ml-4 sm:-ml-6 w-full text-center">
              <!-- Dummy.. -->
              <span class="bg-white inline-flex items-center justify-center h-7 w-20 rounded-t-full shadow focus:outline-none absolute ml-1" style="z-index: -1;" />

              <button
                @click="handleAddBlockAbove"
                class="inline-flex items-center justify-center h-7 w-20 rounded-t-full focus:outline-none">
                <IconHeroiconsSmallPlus class="h-5 w-5 text-gray-300" />
              </button>
            </div>

            <div class="absolute bottom-0 -mb-7 -ml-4 sm:-ml-6 w-full text-center">
              <!-- Dummy.. -->
              <span class="bg-white inline-flex items-center justify-center h-7 w-20 rounded-b-full shadow focus:outline-none absolute ml-1" style="z-index: -1;" />

              <button
                @click="handleAddBlockBelow"
                class="inline-flex items-center justify-center h-7 w-20 rounded-t-full focus:outline-none">
                <IconHeroiconsSmallPlus class="h-5 w-5 text-gray-300" />
              </button>
            </div>

          </template>

        </template>

        <template #collapsed-content="{ isExpanded }">
          <div>
            <h2 v-show="!isExpanded" class="text-2xl font-bold text-center truncate" style="white-space: nowrap;" :contenteditable="false">{{ blockTitle }}</h2>
          </div>
        </template>

        <template #content="{ isExpanded }">
    <!--       <div
            ref="content"
            class="prose"
            :contenteditable="view.editable.toString()"/> -->

          <node-view-content class="prose" />
        </template>

        <template #overlay>


          <transition
            enter-active-class="ease-out duration-300"
            enter-class="transform opacity-0"
            enter-to-class="transform opacity-100"
            leave-active-class="ease-in duration-300"
            leave-class="transform opacity-100"
            leave-to-class="transform opacity-0">

            <div
              v-if="isViewEditable && showSettingsOverlay"
              class="absolute w-full h-full inset-0 rounded-lg cursor-default flex items-center justify-center"
              @click="toggleSettings"
              style="background-color: rgba(255,255,255,0.5); backdrop-filter: blur(4px);">


              <span class="relative z-0 inline-flex shadow-sm rounded-md">
                <button @click="handleDeleteBlock" type="button" class="relative inline-flex items-center px-4 py-2 rounded-l-md border border-gray-300 bg-white text-sm leading-5 font-medium text-gray-700 hover:text-gray-500 focus:z-10 focus:outline-none focus:border-blue-300 focus:shadow-outline-blue active:bg-gray-100 active:text-gray-700 transition ease-in-out duration-150">
                  <IconHeroiconsSmallTrash class="-ml-1 mr-2 h-5 w-5 text-gray-400" />
                  <span>Delete</span>
                </button>

                <button
                  @click="handleControlHitUp"
                  type="button" class="-ml-px relative inline-flex items-center px-2 py-2 border border-gray-300 bg-white text-sm leading-5 font-medium text-gray-400 hover:text-gray-300 focus:z-10 focus:outline-none focus:border-blue-300 focus:shadow-outline-blue active:bg-gray-100 active:text-gray-500 transition ease-in-out duration-150" title="Move card up" aria-label="Move card up">
                  <IconHeroiconsSmallChevronDoubleUp class="h-5 w-5" />
                </button>

                <button
                  @click="handleControlHitDown"
                  type="button" class="-ml-px relative inline-flex items-center px-2 py-2 rounded-r-md border border-gray-300 bg-white text-sm leading-5 font-medium text-gray-400 hover:text-gray-300 focus:z-10 focus:outline-none focus:border-blue-300 focus:shadow-outline-blue active:bg-gray-100 active:text-gray-500 transition ease-in-out duration-150"
                  title="Move card down" aria-label="Move card down">
                  <IconHeroiconsSmallChevronDoubleDown class="h-5 w-5" />
                </button>
              </span>

            </div>

          </transition>

          <button
            v-if="isViewEditable"
            @click="toggleSettings"
            class="absolute w-6 h-6 rounded-full top-1 right-1 flex items-center justify-center focus:outline-none text-gray-200 hover:text-gray-400 transition ease-in-out duration-300"
            title="Toggle Settings">
            <IconHeroiconsSmallCog class="h-5 w-5" />
          </button>

        </template>
      </component>
  </node-view-wrapper>
</template>

<script>
import PositCardPanel from '@/PositEditor/Components/PositCardPanel'
import IconHeroiconsSmallPlus from '@/Icons/IconHeroiconsSmallPlus'
import IconHeroiconsSmallChevronDoubleDown from '@/Icons/IconHeroiconsSmallChevronDoubleDown'
import IconHeroiconsSmallChevronDoubleUp from '@/Icons/IconHeroiconsSmallChevronDoubleUp'
import IconHeroiconsSmallCog from '@/Icons/IconHeroiconsSmallCog'
import IconHeroiconsSmallTrash from '@/Icons/IconHeroiconsSmallTrash'

export default {
  components: {
    PositCardPanel,
    IconHeroiconsSmallPlus,
    IconHeroiconsSmallChevronDoubleDown,
    IconHeroiconsSmallChevronDoubleUp,
    IconHeroiconsSmallCog,
    IconHeroiconsSmallTrash
  },
  // there are some props available
  // `node` is a Prosemirror Node Object
  // `updateAttrs` is a function to update attributes defined in `schema`
  // `view` is the ProseMirror view instance
  // `options` is an array of your extension options
  // `selected` is a boolean which is true when selected
  // `editor` is a reference to the TipTap editor instance
  // `getPos` is a function to retrieve the start position of the node
  // `decorations` is an array of decorations around the node
  // props: ['node', 'updateAttrs', 'view', 'getPos', 'selected', 'decorations', 'options'],
  props: {
    editor: {
      type: Object,
    },

    node: {
      type: Object,
    },

    decorations: {
      type: Array,
    },

    selected: {
      type: Boolean,
    },

    extension: {
      type: Object,
    },

    getPos: {
      type: Function,
    },

    updateAttributes: {
      type: Function,
    },
  },
  computed: {
    parentIsEditable () {
      return this.$parent
    },
    isViewEditable () {
      return this.editor?.['isEditable'] ?? false
    },
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
        this.updateAttributes({
          expanded,
        })
      },
    },
  },
  data () {
    return {
      hasFocus: false,
      showSettingsOverlay: false
    }
  },
  methods: {
    toggleSettings () {
      this.showSettingsOverlay = !this.showSettingsOverlay
    },
    collapsedBlockHeaderContent () {
      console.log(this.node)
      return 'this.$refs.content?.innerText'
    },
    handleControlHitUp () {
      this.$parent.$emit('handleControlHitUp', {
        node: this.node,
        view: this.editor.view,
        startPos: this.getPos()
      })
    },
    handleControlHitDown () {
      console.log(this.$parent)
      this.$parent.$emit('handleControlHitDown', {
        node: this.node,
        view: this.editor.view,
        startPos: this.getPos()
      })
    },
    handlePositBlockFocusOut () {
      console.log('handlePositBlockFocusOut')
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
      console.log(this)
      this.$parent.$emit('handleAddBlockAbove', {
        node: this.node,
        view: this.editor.view,
        startPos: this.getPos()
      })
    },
    handleAddBlockBelow () {
      this.$parent.$emit('handleAddBlockBelow', {
        node: this.node,
        view: this.editor.view,
        startPos: this.getPos()
      })
    },
    handleDeleteBlock () {
      this.$parent.$emit('handleDeleteBlock', {
        node: this.node,
        view: this.editor.view,
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
    // decorations: {
    //   immediate: true,
    //   deep: true,
    //   handler (value) {
    //     this.handleDecorationsChanged(value)
    //   }
    // },
  }
}
</script>
