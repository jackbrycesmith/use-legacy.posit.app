<template>
  <div>
    <slot name="controls"/>
    <div :contenteditable="false" class="bg-white overflow-hidden shadow rounded-lg">
      <div :contenteditable="view.editable.toString()" class="px-4 py-5 sm:p-6 focus:outline-none">
        <slot name="content"/>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  // there are some props available
  // `node` is a Prosemirror Node Object
  // `updateAttrs` is a function to update attributes defined in `schema`
  // `view` is the ProseMirror view instance
  // `options` is an array of your extension options
  // `selected` is a boolean which is true when selected
  // `editor` is a reference to the TipTap editor instance
  // `getPos` is a function to retrieve the start position of the node
  // `decorations` is an array of decorations around the node
  props: ['node', 'updateAttrs', 'view'],
  // Could probably pass these down...
}
</script>
