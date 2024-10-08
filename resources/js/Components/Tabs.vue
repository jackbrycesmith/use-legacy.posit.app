<template>
  <RenderlessTabs
    ref="renderlessTabs"
    :selected-index.sync="selectedIndex__"
    #default="{ orderedTabPanes, isActive, select }">
    <fragment>
      <div :class="tabsClass">
        <div class="border-b border-gray-200">
          <nav
            :class="{ 'justify-center': align === 'center' }"
            class="flex -mb-px">

            <a
              v-for="(tab, index) in orderedTabPanes"
              :key="tab.name"
              :title="tab.title"
              :class="isActive(index) ? activeTabClass : inactiveTabClass"
              class="group inline-flex items-center py-2 px-3 select-none text-sm leading-5 font-medium"
              @click="select(index)">

              <component
                :is="tab.icon"
                :class="{ activeTabIconClass: isActive(index), inactiveTabIconClass : !isActive(index), '-ml-0.5 mr-2': shouldShowTabText(tab, index) }"
                class="h-5 w-5"/>

              <span v-show="shouldShowTabText(tab, index)">
                {{ tab.title }}
              </span>
            </a>

          </nav>
        </div>
      </div>

      <slot name="default"/>
    </fragment>
  </RenderlessTabs>
</template>

<script>
import RenderlessTabs from '@/Components/Renderless/RenderlessTabs'

export default {
  props: {
    selectedIndex: { type: Number, default: -1 },
    align: {
      type: String,
      default: 'center',
      validator (val) {
        return ['center'].includes(val)
      }
    },
    showTabTextWhenInactive: {
      type: Boolean,
      default: true
    },
    tabsClass: { type: String },
    activeTabClass: {
      type: String,
      default: 'border-b-2 border-indigo-500 text-indigo-600 focus:outline-none focus:text-indigo-800 focus:border-indigo-700'
    },
    activeTabIconClass: {
      type: String,
      default: 'text-indigo-500 group-focus:text-indigo-600'
    },
    inactiveTabClass: {
      type: String,
      default: 'cursor-pointer border-b-2 border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 focus:outline-none focus:text-gray-700 focus:border-gray-300'
    },
    inactiveTabIconClass: {
      type: String,
      default: 'text-gray-400 group-hover:text-gray-500 group-focus:text-gray-600'
    },
  },
  components: {
    RenderlessTabs
  },
  data () {
    return {
      selectedIndex__: this.selectedIndex
    }
  },
  watch: {
    selectedIndex (newIndex) {
      this.$refs.renderlessTabs.select(newIndex)
    },
    selectedIndex__ (value) {
      this.$emit('update:selectedIndex', value)
    }
  },
  methods: {
    shouldShowTabText (tab, index) {
      if (this.showTabTextWhenInactive) {
        return true
      }

      return this.$refs.renderlessTabs.isActive(index)
    }
  }
}
</script>
