<template>
  <inertia-link
    :href="$route('use.index')"
    class="w-10 h-10 rounded-full flex items-center justify-center shadow-md"
    style="background-color: rgba(255, 255, 255, 0.5); backdrop-filter: blur(2px);">
    <ApplicationMark class="w-6 h-6" />
  </inertia-link>
</template>

<script>
import ApplicationMark from '@/Jetstream/ApplicationMark'

export default {
  components: { ApplicationMark },
  data () {
    return {

    }
  }
}
</script>
