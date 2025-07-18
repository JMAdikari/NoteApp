<template>
  <div class="space-y-4">
    <div v-if="notes.length">
      <div
        v-for="note in notes"
        :key="note.id"
        class="bg-white/95 backdrop-blur-sm p-6 rounded-lg shadow-lg hover:shadow-xl transition-all transform hover:-translate-y-1 relative"
      >
        <div class="absolute top-4 right-4 text-white text-sm bg-gray-800/80 p-2 rounded-md">
          <p>Created: {{ formatDate(note.created_at) }}</p>
          <p>Updated: {{ formatDate(note.updated_at) }}</p>
        </div>
        <h3 class="text-xl font-semibold text-gray-800 mb-2">{{ note.title }}</h3>
        <p class=" text-white text-sm bg-gray-800/80 p-2 rounded-md">{{ note.body || 'No content' }}</p>
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
    <p v-else class=" text-center text-white  p-3 rounded-md">No notes yet. Start adding some!</p>
  </div>
</template>

<script>
import api from '@/axios';
import { format } from 'date-fns';
import { ElMessage } from 'element-plus';

export default {
  props: ['notes'],
  methods: {
    async deleteNote(id) {
      if (confirm('Are you sure you want to delete this note?')) {
        try {
          await api.delete(`/notes/${id}`);
          ElMessage({
            message: 'Note deleted successfully!',
            type: 'success',
            duration: 3000
          });
          this.$emit('refresh');
        } catch (error) {
          console.error('Error deleting note:', error);
          ElMessage({
            message: 'Failed to delete note. Please try again.',
            type: 'error',
            duration: 3000
          });
        }
      }
    },
    async clearAllNotes() {
      if (confirm('Are you sure you want to delete all notes? This action cannot be undone.')) {
        try {
          await api.delete('/notes');
          ElMessage({
            message: 'All notes deleted successfully!',
            type: 'success',
            duration: 3000
          });
          this.$emit('refresh');
        } catch (error) {
          console.error('Error clearing notes:', error);
          ElMessage({
            message: 'Failed to delete all notes. Please try again.',
            type: 'error',
            duration: 3000
          });
        }
      }
    },
    edit(id) {
      this.$router.push(`/notes/${id}/edit`);
    },
    formatDate(dateString) {
      if (!dateString) return 'N/A';
      return format(new Date(dateString), 'MM/dd/yyyy hh:mm a');
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