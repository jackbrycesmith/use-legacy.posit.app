<template>
  <fragment>
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
      <!-- We've used 3xl here, but feel free to try other max-widths based on your needs -->
      <div class="max-w-3xl mx-auto">
        <!-- Content goes here -->

        <div class="bg-white overflow-hidden shadow rounded-lg mt-5">
          <div class="px-4 py-5 sm:p-6">
            <!-- Content goes here -->
            <editor-content :editor="editor"/>
          </div>
        </div>

      </div>
    </div>
  </fragment>
</template>

<script>
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
    proposal: { type: Object }
  },
  data () {
    return {
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
}
</script>
