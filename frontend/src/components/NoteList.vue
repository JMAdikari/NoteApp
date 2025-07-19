<template>
  <div class="space-y-4">
    <div v-if="notes.length">
      <div
        v-for="note in notes"
        :key="note.id"
        class="bg-gray-800/95 backdrop-blur-sm p-6 rounded-lg shadow-lg hover:shadow-xl transition-all transform hover:-translate-y-1 relative border border-gray-700"
      >
        <div class="absolute top-4 right-4 text-gray-300 text-sm bg-gray-900/80 p-2 rounded-md border border-gray-600">
          <p>Created: {{ formatDate(note.created_at) }}</p>
          <p>Updated: {{ formatDate(note.updated_at) }}</p>
        </div>
        <h3 class="text-xl font-semibold text-white mb-2">{{ note.title }}</h3>
        <p class="text-gray-300 mb-4">{{ note.body || 'No content' }}</p>
        <div class="flex gap-3">
          <button
            @click="edit(note.id)"
            class="bg-blue-700 text-white px-4 py-2 rounded-full font-semibold hover:bg-blue-800 transition-all transform hover:scale-105 shadow border border-blue-600"
          >
            Edit
          </button>
          <button
            @click="deleteNote(note.id)"
            class="bg-red-700 text-white px-4 py-2 rounded-full font-semibold hover:bg-red-800 transition-all transform hover:scale-105 shadow border border-red-600"
          >
            Delete
          </button>
        </div>
      </div>
    </div>
    <p v-else class="text-center text-white text-lg">No notes yet. Start adding some!</p>
  </div>
</template>

<script>
import { api } from '@/axios';
import { format } from 'date-fns';
import { ElMessage } from 'element-plus';

export default {
  props: ['notes'],
  methods: {
    async deleteNote(id) {
      if (confirm('Are you sure you want to delete this note?')) {
        try {
          await api.delete(`/notes/${id}`);
          this.$emit('refresh');
          
          // Show success message
          ElMessage({
            message: 'Note deleted successfully!',
            type: 'success',
            duration: 3000,
          });
        } catch (error) {
          console.error('Error deleting note:', error);
          
          // Show error message
          ElMessage({
            message: 'Error deleting note. Please try again.',
            type: 'error',
            duration: 4000,
          });
          
          if (error.response?.status === 401) {
            localStorage.removeItem('token');
            localStorage.removeItem('user');
            this.$router.push('/auth');
          }
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
div {
  transform: translateZ(0);
}
</style>