<template>
  <form @submit.prevent="handleSubmit" class="space-y-6 bg-white/95 backdrop-blur-sm p-6 rounded-lg shadow-lg">
    <input
      v-model="title"
      placeholder="Note Title"
      required
      class="w-full p-4 rounded-md border border-gray-200 focus:border-blue-400 focus:ring focus:ring-blue-100 focus:ring-opacity-50 text-gray-900 bg-white"
    />
    <textarea
      v-model="body"
      placeholder="Note body (optional)"
      class="w-full p-4 rounded-md border border-gray-200 focus:border-blue-400 focus:ring focus:ring-blue-100 focus:ring-opacity-50 text-gray-900 bg-white h-40"
    ></textarea>
    <div class="flex gap-4">
      <button
        type="submit"
        class="bg-blue-500 text-white px-6 py-3 rounded-full font-semibold hover:bg-blue-600 transition-all transform hover:scale-105 shadow-md"
      >
        Add Note
      </button>
    </div>
  </form>
</template>

<script>
import api from '@/axios';

export default {
  data() {
    return {
      title: '',
      body: '',
    };
  },
  methods: {
    async handleSubmit() {
      try {
        await api.post('/notes', { title: this.title, body: this.body });
        this.resetForm();
        this.$emit('refresh');
      } catch (error) {
        console.error('Error saving note:', error);
      }
    },
    resetForm() {
      this.title = '';
      this.body = '';
    },
  },
};
</script>

<style scoped>
/* Ensure 3D transform compatibility */
form {
  transform: translateZ(0);
}

/* Hover effect for form */
form:hover {
  transform: translateY(-2px) translateZ(5px);
}
</style>