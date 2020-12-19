<template>
  <div class="relative">

    <EditorContent
      :editor="editor"/>

  </div>
</template>

<script>
import { Editor, EditorContent, defaultExtensions } from '@tiptap/vue-starter-kit'
import PositBlockNode from '@/PositEditor/Nodes/PositBlockNode'
import PositLayoutDocOne from '@/PositEditor/Nodes/PositLayoutDocOne'

export default {
  components: {
    EditorContent,
  },
  data () {
    return {
      keepInBounds: true,
      editor: new Editor({
        editable: false,
        editorProps: {
          attributes: {
            class: 'outline-none space-y-2/12'
          }
        },
        extensions: defaultExtensions(),
        onInit: this.onEditorInit,
      })
    }
  },
  methods: {
    onEditorInit ({ state, view }) {
      // console.log('onEditorInit')
      // console.log(state)
      // console.log(view)
    },
  },
  beforeDestroy() {
    this.editor.destroy()
  },
}
</script>
