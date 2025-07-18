<template>
  <div v-if="notes.length">
    <div v-for="note in notes" :key="note.id">
      <h3>{{ note.title }}</h3>
      <p>{{ note.body }}</p>
      <button @click="edit(note)">Edit</button>
      <button @click="deleteNote(note.id)">Delete</button>
    </div>
  </div>
  <p v-else>No notes yet.</p>
</template>

<script>
import api from '@/axios';

export default {
  props: ['notes'],
  methods: {
    async deleteNote(id) {
      if (confirm('Are you sure you want to delete this note?')) {
        await api.delete(`/notes/${id}`);
        this.$emit('refresh');
      }
    },
    edit(note) {
      this.$emit('update:editNote', note);
    },
  },
};
</script>
