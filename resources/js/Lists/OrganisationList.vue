<template>
  <div>
    <h2 class="text-gray-500 text-xs font-medium uppercase tracking-wide">Organisations</h2>
    <ul class="mt-3 grid grid-cols-1 gap-5 sm:gap-6 sm:grid-cols-2 lg:grid-cols-4">
      <OrganisationListItem
        v-for="org in orgs"
        :key="org.uuid"
        :org="org"
      />
    </ul>
  </div>
</template>

<script>
import OrganisationListItem from '@/Lists/OrganisationListItem'

export default {
  components: {
    OrganisationListItem
  },
  props: {
    orgs: { type: Array, default: () => [] }
  },
  data () {
    return {

    }
  }
}
</script>
