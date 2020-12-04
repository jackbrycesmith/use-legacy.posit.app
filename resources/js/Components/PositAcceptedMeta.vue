<template>
  <fragment>
    <div class="text-center mt-10">
      <IconHeroiconsMediumCheck class="h-28 w-28 md:w-40 md:w-40 text-black inline-block" />
    </div>

    <div class="mt-4 relative max-w-screen-xl mx-auto px-4 sm:px-6 lg:px-8 lg:mt-5">
        <div class="max-w-md mx-auto lg:max-w-5xl">
            <div class="rounded-lg px-6 lg:flex lg:items-center">
                <div class="flex-1">
                    <div class="text-lg leading-7 text-gray-600 text-center">
                      Accepted
                    </div>
                </div>
            </div>
        </div>
    </div>
  </fragment>
</template>

<script>
import IconHeroiconsMediumCheck from '@/Icons/IconHeroiconsMediumCheck'

export default {
  name: 'PositAcceptedMeta',
  components: {
    IconHeroiconsMediumCheck,
  },
  data () {
    return {

    }
  }
}
</script>
