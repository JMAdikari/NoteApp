<template>
  <form @submit.prevent="submit" class="space-y-6 bg-gray-800/95 backdrop-blur-sm p-6 rounded-lg shadow-lg border border-gray-700">
    <input
      v-model="title"
      placeholder="Note Title"
      required
      class="w-full p-4 rounded-md border border-gray-600 focus:border-gray-500 focus:ring focus:ring-gray-500 focus:ring-opacity-50 text-white bg-gray-700 placeholder-gray-400"
    />
    <textarea
      v-model="body"
      placeholder="Note body (optional)"
      class="w-full p-4 rounded-md border border-gray-600 focus:border-gray-500 focus:ring focus:ring-gray-500 focus:ring-opacity-50 text-white bg-gray-700 h-40 placeholder-gray-400"
    ></textarea>
    <div v-if="error" class="text-red-600 text-sm">{{ error }}</div>
    <div class="flex gap-4">
      <button
        type="submit"
        class="bg-gray-700 text-white px-6 py-3 rounded-full font-semibold hover:bg-gray-800 transition-all transform hover:scale-105 shadow-md border border-gray-600"
      >
        Add Note
      </button>
    </div>
  </form>
</template>

<script>
import { api } from '@/axios';
import { ElMessage } from 'element-plus';

export default {
  data() {
    return {
      title: '',
      body: '',
      error: null,
    };
  },
  methods: {
    async submit() {
      try {
        this.error = null;
        await api.post('/notes', { title: this.title, body: this.body });
        this.title = '';
        this.body = '';
        this.$emit('refresh');
        
        // Show success message
        ElMessage({
          message: 'Note created successfully!',
          type: 'success',
          duration: 3000,
        });
      } catch (error) {
        console.error('Error creating note:', error);
        console.error('Response data:', error.response?.data);
        console.error('Response status:', error.response?.status);
        
        this.error = error.response?.data?.message || 'An error occurred';
        
        // Show error message
        ElMessage({
          message: this.error,
          type: 'error',
          duration: 4000,
        });
        
        if (error.response?.status === 401) {
          localStorage.removeItem('token');
          localStorage.removeItem('user');
          this.$router.push('/auth');
        }
      }
    },
  },
};
</script>

<style scoped>
form {
  transform: translateZ(0);
}
form:hover {
  transform: translateY(-2px) translateZ(5px);
}
</style>