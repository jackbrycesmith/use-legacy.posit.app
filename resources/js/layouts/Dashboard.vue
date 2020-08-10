<template>
  <fragment>
    <!-- Background color split screen for large screens -->
    <div class="fixed top-0 left-0 w-1/2 h-full"></div>
    <div class="fixed top-0 right-0 w-1/2 h-full bg-yellow-50"></div>
    <div class="relative min-h-screen flex flex-col">

      <DashboardNavbar class="flex-shrink-0" />

      <slot />
    </div>
  </fragment>
</template>

<script>
import DashboardNavbar from '@/Components/DashboardNavbar'

export default {
  components: {
    DashboardNavbar
  },
  metaInfo: () => ({
    htmlAttrs: {
      class: ['h-full', 'bg-yellow-50']
      // TODO this removes existing class attributes on html... so maybe remove from app.blade.php & then follow the rule of allowing to ovveride bodyAttrs for classes?
    }
  }),
  data () {
    return {

    }
  },
  mounted () {
    // setTimeout(() => {
    //   console.log(this.$page)
    // }, 1000)
  }
}
</script>
