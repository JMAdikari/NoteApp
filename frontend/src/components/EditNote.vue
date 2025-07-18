<template>
  <div class="min-h-screen relative overflow-hidden">
    <div class="absolute inset-0 bg-gradient-to-br from-blue-500 via-purple-500 to-pink-500 animate-gradient z-0"></div>
    <div class="relative z-10 container mx-auto px-4 py-8">
      <div class="text-center mb-12">
        <h1 class="text-4xl md:text-5xl font-bold text-white drop-shadow-lg mb-4">
          📝 Edit Note
        </h1>
        <p class="text-lg md:text-xl text-white/80 max-w-2xl mx-auto">
          Update your note below.
        </p>
      </div>
      <div class="bg-white/90 backdrop-blur-sm rounded-lg shadow-2xl p-6 max-w-3xl mx-auto">
        <form @submit.prevent="submit" class="space-y-6">
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
          <div v-if="error" class="text-red-600 text-sm">{{ error }}</div>
          <div class="flex gap-4">
            <button
              type="submit"
              class="bg-blue-500 text-white px-6 py-3 rounded-full font-semibold hover:bg-blue-600 transition-all transform hover:scale-105 shadow-md"
            >
              Update Note
            </button>
            <button
              @click.prevent="$router.push('/')"
              class="bg-gray-400 text-white px-6 py-3 rounded-full font-semibold hover:bg-gray-500 transition-all transform hover:scale-105 shadow-md"
            >
              Cancel
            </button>
          </div>
        </form>
      </div>
    </div>
  </div>
</template>

<script>
import { api } from '@/axios';

export default {
  data() {
    return {
      title: '',
      body: '',
      id: this.$route.params.id,
      error: null,
    };
  },
  methods: {
    async fetchNote() {
      try {
        const res = await api.get(`/notes/${this.id}`);
        this.title = res.data.title;
        this.body = res.data.body || '';
      } catch (error) {
        this.error = error.response?.data?.message || 'Error fetching note';
        if (error.response?.status === 401) {
          localStorage.removeItem('token');
          localStorage.removeItem('user');
          this.$router.push('/auth');
        }
      }
    },
    async submit() {
      try {
        this.error = null;
        await api.put(`/notes/${this.id}`, { title: this.title, body: this.body });
        this.$router.push('/');
      } catch (error) {
        this.error = error.response?.data?.message || 'Error updating note';
        if (error.response?.status === 401) {
          localStorage.removeItem('token');
          localStorage.removeItem('user');
          this.$router.push('/auth');
        }
      }
    },
  },
  mounted() {
    this.fetchNote();
  },
};
</script>

<style scoped>
@keyframes gradient {
  0% { background-position: 0% 50%; }
  50% { background-position: 100% 50%; }
  100% { background-position: 0% 50%; }
}
.animate-gradient {
  background-size: 200% 200%;
  animation: gradient 15s ease infinite;
}
form {
  transform: translateZ(0);
}
form:hover {
  transform: translateY(-2px) translateZ(5px);
}
</style>