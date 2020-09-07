<template>
  <BaseVideoRecord
    ref="baseVideoRecord"
    :expand-animation="handleExpandAnimation"
    :collapse-animation="handleCollapseAnimation">
    <template #template="{ currentState, context, sendEvent }">

      <!-- Collapsed state... -->
      <div
        ref="collapsedCircle"
        v-if="currentState.matches('collapsed') || currentState.matches('expanding') || currentState.matches('collapsing')"
        class="bg-white h-32 w-32 rounded-full flex items-center justify-center hover:bg-gray-50 cursor-pointer absolute bottom-0 left-auto right-auto -mb-16 shadow-md"
        @click="handleExpand">

        <IconVideoMessage class="h-8 w-8 text-red-400" />
      </div>

      <!-- Expanded state... -->
      <portal to="proposal-view-portal">

        <BaseModal
          ref="modal"
          :is-visible="currentState.matches('expanded') || currentState.matches('expanding')"
          :on-background-hit="handleExpandedClick"
          :on-escape-key-hit="handleExpandedClick"
          :root-visible-hide-delay="300"
          :show-default-modal-style="false"
          modal-root-class="fixed bottom-0 inset-x-0 px-4 pb-4 inset-0 flex items-center justify-center"
        >
          <template #alternative-modal>
            <div
              v-if="currentState.matches('expanded') || currentState.matches('expanding') || currentState.matches('collapsing')"
              ref="expandedCircle"
              class="relative bg-white h-72 w-72 sm:h-96 sm:w-96 rounded-full flex items-center justify-center hover:bg-gray-50 cursor-pointer shadow-md">

              <!-- <IconVideoMessage class="h-8 w-8 text-red-400" /> -->
<!--               <video
                ref="video"
                playsinline
                class="video-js vjs-default-skin w-full h-full rounded-full"
                style="object-fit: cover; border-radius: 9999px;"
                ></video> -->

            </div>
          </template>
        </BaseModal>

      </portal>


    </template>
  </BaseVideoRecord>
</template>

<script>
import { BaseVideoRecord } from '@/Components/BaseVideoRecord'
import { snapshotCache } from '@/plugins/SharedElementPlugin'
import { illusory } from 'illusory'
import IconVideoMessage from '@/Components/IconVideoMessage'
import BaseModal from '@/Modals/BaseModal'

export default {
  components: {
    BaseVideoRecord, IconVideoMessage, BaseModal
  },
  props: {
    proposal: { type: Object }
  },
  data () {
    return {}
  },
  computed: {
    isExpanded: {
      get () {
        return this.$refs.baseVideoRecord.currentState.matches('expanded')
      },
      set (value) {
        if (!value) {
          this.$refs.baseVideoRecord.sendEvent('COLLAPSE')
        } else {
          this.$refs.baseVideoRecord.sendEvent('EXPAND')
        }
      }
    }
  },
  mounted () {

  },
  methods: {
    handleExpand () {
      this.$refs.baseVideoRecord.sendEvent('EXPAND')
    },
    handleExpandedClick () {
      this.$refs.baseVideoRecord.sendEvent('COLLAPSE')
    },
    async handleExpandAnimation () {
      console.log('handleExpandAnimation')
      console.log(this.$refs.collapsedCircle, this.$refs.expandedCircle)
      console.log(this.$refs.baseVideoRecord.currentState.matches('collapsed'))
      // this.$refs.modal.isVisible__ = true
      // await new Promise(resolve => setTimeout(resolve, 400))
      await this.$nextTick(async () => {
        await this.$nextTick()
        console.log(this.$refs.collapsedCircle, this.$refs.expandedCircle)
        let { finished, cancel } = illusory(this.$refs.collapsedCircle, this.$refs.expandedCircle)

        await finished
      })
      // await this.$nextTick()
    },
    async handleCollapseAnimation () {
      console.log('handleCollapseAnimation')
      // console.log(this.$refs.collapsedCircle, this.$refs.expandedCircle)
      // this.$refs.modal.isVisible__ = false
      console.log(this.$refs.baseVideoRecord.currentState.matches('expanded'))
      await this.$nextTick(async () => {
        console.log(this.$refs.baseVideoRecord.currentState.matches('collapsing'))
        await this.$nextTick()
        console.log(this.$refs.collapsedCircle, this.$refs.expandedCircle)
        let { finished, cancel } = illusory(this.$refs.expandedCircle, this.$refs.collapsedCircle)

        await finished
      })
    },
  }
}
</script>
