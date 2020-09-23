<template>
  <editor-menu-bubble
    :editor="editor"
    :keep-in-bounds="keepInBounds"
    v-slot="{ commands, isActive, menu }">
    <div
      class="menububble"
      :class="{ 'is-active': menu.isActive }"
      :style="`left: ${menu.left}px; bottom: ${menu.bottom}px;`"
    >

      <button
        class="menububble__button"
        :class="{ 'is-active': isActive.bold() }"
        @click="commands.bold"
      >
        <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" aria-hidden="true" focusable="false" role="img" class="iconify iconify--ic" width="1em" height="1em" preserveAspectRatio="xMidYMid meet" viewBox="0 0 24 24" style="transform: rotate(360deg);"><path d="M15.6 10.79c.97-.67 1.65-1.77 1.65-2.79c0-2.26-1.75-4-4-4H7v14h7.04c2.09 0 3.71-1.7 3.71-3.79c0-1.52-.86-2.82-2.15-3.42zM10 6.5h3c.83 0 1.5.67 1.5 1.5s-.67 1.5-1.5 1.5h-3v-3zm3.5 9H10v-3h3.5c.83 0 1.5.67 1.5 1.5s-.67 1.5-1.5 1.5z" fill="currentColor"></path></svg>
      </button>

      <button
        class="menububble__button"
        :class="{ 'is-active': isActive.italic() }"
        @click="commands.italic"
      >
        <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" aria-hidden="true" focusable="false" role="img" class="iconify iconify--mdi" width="1em" height="1em" preserveAspectRatio="xMidYMid meet" viewBox="0 0 24 24" style="transform: rotate(360deg);"><path d="M10 4v3h2.21l-3.42 8H6v3h8v-3h-2.21l3.42-8H18V4h-8z" fill="currentColor"></path></svg>
      </button>

      <button
        class="menububble__button"
        :class="{ 'is-active': isActive.underline() }"
        @click="commands.underline"
      >
        <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" aria-hidden="true" focusable="false" role="img" class="iconify iconify--ic" width="1em" height="1em" preserveAspectRatio="xMidYMid meet" viewBox="0 0 24 24" style="transform: rotate(360deg);"><path d="M12 17c3.31 0 6-2.69 6-6V3h-2.5v8c0 1.93-1.57 3.5-3.5 3.5S8.5 12.93 8.5 11V3H6v8c0 3.31 2.69 6 6 6zm-7 2v2h14v-2H5z" fill="currentColor"></path></svg>
      </button>

    </div>
  </editor-menu-bubble>
</template>

<script>
import { EditorMenuBubble } from 'tiptap'

export default {
  components: { EditorMenuBubble },
  props: {
    keepInBounds: { type: Boolean, default: true },
    editor: { type: Object },
  },
  data () {
    return {

    }
  }
}
</script>

<style scoped>
.menububble {
  position: absolute;
  display: flex;
  z-index: 20;
  background: black;
  border-radius: 5px;
  padding: 0.3rem;
  margin-bottom: 0.5rem;
  transform: translateX(-50%);
  visibility: hidden;
  opacity: 0;
  transition: opacity 0.2s, visibility 0.2s;
}

.menububble.is-active {
  opacity: 1;
  visibility: visible;
}

.menububble__button {
  display: inline-flex;
  background: transparent;
  border: 0;
  color: #fff;
  padding: 0.2rem 0.5rem;
  margin-right: 0.2rem;
  border-radius: 3px;
  cursor: pointer;

}

.menububble__button:last-child {
  margin-right: 0;
}

.menububble__button:hover {
  background-color: #ffffff30;
}

.menububble__button.is-active {
  background-color: #ffffff40;
}

.menububble__button__form {
  display: flex;
  align-items: center;
}

.menububble__button__input {
  font: inherit;
  border: none;
  background: transparent;
  color: #fff;
}
</style>
