<template>
  <div class="relative bg-cool-gray-300 flex flex-col justify-center items-center" style="height: 50vh; border-bottom-left-radius: 50% 20%; border-bottom-right-radius: 50% 20%;">

    <ContentEditable
      class="outline-none max-w-screen-xl text-center text-4xl tracking-tight leading-10 font-extrabold text-gray-900 sm:text-5xl sm:leading-none md:text-6xl"
      tag="h1"
      :model.sync="title" />


  </div>
</template>

<script>
import ContentEditable from '@/Components/ContentEditable'

export default {
  components: {
    ContentEditable
  },
  data () {
    return {
      title: 'Proposal'
    }
  }
}
</script>

<style lang="css" scoped>
</style>
