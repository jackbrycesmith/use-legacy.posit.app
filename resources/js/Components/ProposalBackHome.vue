<template>
  <inertia-link :href="$route('use.index')" class="w-10 h-10 bg-primary-yellow-200 rounded-full flex items-center justify-center hover:bg-primary-yellow-100 shadow-md">
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
