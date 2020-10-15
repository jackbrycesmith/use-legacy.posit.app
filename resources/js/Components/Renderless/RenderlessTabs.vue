<script>
import { find, orderBy } from 'lodash-es'

export default {
  props: {
    selectedIndex: {
      type: Number,
      default: -1
    },
  },
  data () {
    return {
      tabPanes: [],
      selectedIndex__: this.selectedIndex
    }
  },
  computed: {
    orderedTabPanes () {
      return orderBy(this.tabPanes, 'seq')
    },
  },
  watch: {
    selectedIndex (newIndex) {
      this.select(newIndex)
    },
    selectedIndex__ (value) {
      this.$emit('update:selectedIndex', value)
    }
  },
  mounted () {
    if (this.selectedIndex__ === -1) {
      this.select(0)
    }
  },
  methods: {
    isActive (index) {
      return index === this.selectedIndex__
    },
    select (index) {
      if (this.selectedIndex__ !== index) {
        this.$emit('tabs-selected', index)
        this.selectedIndex__ = index
      }
    },
    selectTabPaneId (id) {
      const tabPane = find(this.tabPanes, { id })
      if (tabPane) {
        const index = this.orderedTabPanes.indexOf(tabPane)
        this.select(index)
      }
    },
    deleteTab (tab) {
      const index = this.tabPanes.indexOf(tab)
      if (index === -1) return
      this.tabPanes.splice(index, 1)
    },
    // shouldHideTabTitleOnMobile (tab, index) {
    //   if (tab.icon && !this.showTabTitleOnSelectedMobile) {
    //     return true
    //   } else {
    //     return !this.isActive(index)
    //   }
    // }
  },
  render () {
    return this.$scopedSlots.default({
      orderedTabPanes: this.orderedTabPanes,
      isActive: this.isActive,
      select: this.select
    })
  }
}
</script>
