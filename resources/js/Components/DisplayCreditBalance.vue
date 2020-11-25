<template>
  <div class="flex flex-col">
    <dt class="text-sm font-medium text-gray-500 truncate">Available Balance</dt>
    <dd class="text-2xl font-semibold text-gray-900">
      {{ amount }} Credits
    </dd>
  </div>
</template>

<script>
export default {
  name: 'DisplayCreditBalance',
  props: {
    amount: {
      type: Number,
      default: 0
    }
  },
  data () {
    return {

    }
  }
}
</script>
