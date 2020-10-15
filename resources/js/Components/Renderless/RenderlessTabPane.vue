<script>
export default {
  props: {
    title: { type: String, required: true },
    order: { type: Number, default: 0 },
    iconRef: { type: String },
    icon: {}
  },
  data () {
    return {
      paneDidLoad: false,
      icon__: null
    }
  },
  computed: {
    seq () {
      return this.order
    },
    index () {
      return this.$parent.$parent.orderedTabPanes.indexOf(this)
    },
    isSelected () {
      return this.$parent.$parent.selectedIndex__ === this.index
    },
    contentInstance () {
      if (!this.$slots.default || this.$slots.default.length === 0) return null

      return this.$slots.default[0].componentInstance
    }
  },
  watch: {
    '$parent.$parent.selectedIndex__' (value) {
      if (this.index === value) {
        this.$nextTick(() => {
          this.handleSelected()
        })
        this.$emit('tab-selected', this.title)
      }
    }
  },
  created () {
    this.$parent.$parent.tabPanes.push(this)
  },
  beforeDestroy () {
    this.$parent.$parent.deleteTab(this)
  },
  methods: {
    resetPaneDidLoad () {
      this.paneDidLoad = false
    },
    handleSelected () {
      if (this.paneDidLoad) return
      this.paneDidLoad = true
      this.$emit('did-load')
      if (!this.contentInstance) return
      if (!this.contentInstance.__paneDidLoad__ && !(this.contentInstance.__paneDidLoad__ instanceof Function)) return

      this.contentInstance.__paneDidLoad__()
    }
  },
  render () {
    return this.$scopedSlots.default({
      isSelected: this.isSelected,
    })
  }
}
</script>
