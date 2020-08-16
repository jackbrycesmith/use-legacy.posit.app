<template>
  <fragment>
    <div v-if="!is_draft" class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
      <!-- We've used 3xl here, but feel free to try other max-widths based on your needs -->
      <div class="max-w-3xl mx-auto">
        <!-- Content goes here -->

        <div class="bg-white overflow-hidden shadow rounded-lg mt-5">
          <div class="px-4 py-5 sm:p-6">
            <!-- Content goes here -->
            <editor-content :editor="editor"/>
          </div>
        </div>

        <div class="text-center mt-5">
          <span class="inline-flex rounded-md shadow-sm">
            <button @click="handlePaymentClick" :disabled="stripe === null" type="button" class="inline-flex items-center px-6 py-3 border border-gray-300 text-base leading-6 font-medium rounded-md text-gray-700 bg-white hover:text-gray-500 focus:outline-none focus:border-blue-300 focus:shadow-outline-blue active:text-gray-800 active:bg-gray-50 transition ease-in-out duration-150">
              Payment
            </button>
          </span>
        </div>

      </div>
    </div>

    <!-- TODO this is naive, should really put in own component -->
    <div v-else class="h-screen flex justify-center items-center">
      <div>
        <img class="h-24 m-auto mb-3" src="/posit-icon.png"/>
        <h3 class="text-2xl leading-6 font-medium text-gray-900 text-center">
          Proposal in draft...
        </h3>
        <p class="mt-3 text-sm leading-5 text-gray-500 text-center">
          Please ask the creator to publish.
        </p>
      </div>
    </div>
  </fragment>
</template>

<script>
import { loadStripe } from '@stripe/stripe-js'
import { Editor, EditorContent } from 'tiptap'

import {
  Blockquote,
  BulletList,
  CodeBlock,
  HardBreak,
  Heading,
  ListItem,
  OrderedList,
  TodoItem,
  TodoList,
  Bold,
  Code,
  Italic,
  Link,
  Strike,
  Underline,
  History,
} from 'tiptap-extensions'

export default {
  components: { EditorContent },
  props: {
    proposal: { type: Object },
    is_draft: { type: Boolean, default: false },
    stripe_pub_key: {}
  },
  data () {
    return {
      stripe: null,
      keepInBounds: true,
      editor: new Editor({
        editorProps: {
          attributes: {
            class: 'prose'
          }
        },
        editable: false,
        extensions: [
          new Blockquote(),
          new BulletList(),
          new CodeBlock(),
          new HardBreak(),
          new Heading({ levels: [1, 2, 3] }),
          new ListItem(),
          new OrderedList(),
          new TodoItem(),
          new TodoList(),
          new Link(),
          new Bold(),
          new Code(),
          new Italic(),
          new Strike(),
          new Underline(),
          new History(),
        ],
      })
    }
  },
  watch: {
    proposal: {
      immediate: true,
      handler (value) {
        this.editor.setContent(value.data?.content?.content)
      }
    }
  },
  mounted () {
    setTimeout(async () => {
      let stripe = await loadStripe(this.stripe_pub_key, {
        stripeAccount: this.proposal.data.stripe_account_id
      })
      this.stripe = stripe
      // (async function(thing =) {
      // })()
      console.log(this.proposal, this.stripe_pub_key)
    }, 1000)
  },
  methods: {
    handlePaymentClick () {
      this.stripe.redirectToCheckout({
        // Make the id field from the Checkout Session creation API response
        // available to this file, so you can provide it as argument here
        // instead of the {{CHECKOUT_SESSION_ID}} placeholder.
        sessionId: this.proposal.data.stripe_checkout_session_id
      })
    }
  }
}
</script>
