<template>
  <div class="inline-flex justify-center items-center h-10 w-10 bg-white text-orange-400 rounded-full shadow-md select-none">
    <template v-if="proposal.creator_has_profile_photo">
      <img :src="proposal.creator_profile_photo_url" alt="Creator profile photo" class="rounded-full h-9 w-9 object-cover">
    </template>
    <template v-else>
      {{ proposal.creator_initials }}
    </template>
  </div>
</template>

<script>
export default {
  name: 'PositCreatorAvatar',
  props: {
    proposal: { type: Object },
  },
}
</script>
