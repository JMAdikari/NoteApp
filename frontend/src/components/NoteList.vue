<template>
  <div class="space-y-4">
    <div v-if="notes.length">
      <div
        v-for="note in notes"
        :key="note.id"
        class="bg-white/95 backdrop-blur-sm p-6 rounded-lg shadow-lg hover:shadow-xl transition-all transform hover:-translate-y-1"
      >
        <h3 class="text-xl font-semibold text-gray-800 mb-2">{{ note.title }}</h3>
        <p class="text-gray-700 mb-4">{{ note.body || 'No content' }}</p>
        <div class="flex gap-3">
          <button
            @click="edit(note.id)"
            class="bg-blue-600 text-white px-4 py-2 rounded-full font-semibold hover:bg-blue-700 transition-all transform hover:scale-105 shadow"
          >
            Edit
          </button>
          <button
            @click="deleteNote(note.id)"
            class="bg-red-600 text-white px-4 py-2 rounded-full font-semibold hover:bg-red-700 transition-all transform hover:scale-105 shadow"
          >
            Delete
          </button>
        </div>
      </div>
    </div>
    <p v-else class="text-center text-gray-500 text-lg">No notes yet. Start adding some!</p>
  </div>
</template>

<script>
import api from '@/axios';

export default {
  props: ['notes'],
  methods: {
    async deleteNote(id) {
      if (confirm('Are you sure you want to delete this note?')) {
        try {
          await api.delete(`/notes/${id}`);
          this.$emit('refresh');
        } catch (error) {
          console.error('Error deleting note:', error);
        }
      }
    },
    edit(id) {
      this.$router.push(`/notes/${id}/edit`);
    },
  },
};
</script>

<style scoped>
/* Ensure 3D transform compatibility */
div {
  transform: translateZ(0);
}
</style>