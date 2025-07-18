<template>
  <div class="min-h-screen relative overflow-hidden">
    <div class="absolute inset-0 bg-gradient-to-br from-blue-500 via-purple-500 to-pink-500 animate-gradient z-0"></div>
    <div class="relative z-10 container mx-auto px-4 py-8">
      <div class="text-center mb-12">
        <h1 class="text-4xl md:text-5xl font-bold text-white drop-shadow-lg mb-4">
          📝 My Notes
        </h1>
        <p class="text-lg md:text-xl text-white/80 max-w-2xl mx-auto">
          Welcome, {{ user?.name || 'User' }}! Manage your notes below.
        </p>
      </div>
      <div class="bg-white/90 backdrop-blur-sm rounded-lg shadow-2xl p-6 mb-8 max-w-3xl mx-auto">
        <NoteForm @refresh="getNotes" />
      </div>
      <div class="bg-white/90 backdrop-blur-sm rounded-lg shadow-2xl p-6 max-w-3xl mx-auto">
        <NoteList :notes="notes" @refresh="getNotes" />
      </div>
      <div class="flex justify-center gap-4 mt-8">
        <button
          class="bg-purple-600 text-white px-6 py-3 rounded-full font-semibold hover:bg-purple-700 transition-all transform hover:scale-105 shadow-lg"
          @click="clearAllNotes"
        >
          Clear All Notes
        </button>
        <button
          class="bg-red-600 text-white px-6 py-3 rounded-full font-semibold hover:bg-red-700 transition-all transform hover:scale-105 shadow-lg"
          @click="logout"
        >
          Logout
        </button>
      </div>
    </div>
  </div>
</template>

<script>
import NoteForm from '@/components/NoteForm.vue';
import NoteList from '@/components/NoteList.vue';
import { api } from '@/axios';
import { ElMessage } from 'element-plus';

export default {
  components: { NoteForm, NoteList },
  data() {
    return {
      notes: [],
      user: JSON.parse(localStorage.getItem('user')) || null,
    };
  },
  methods: {
    async getNotes() {
      try {
        // Debug: Check if we have a token and user
        const token = localStorage.getItem('token');
        const user = JSON.parse(localStorage.getItem('user') || 'null');
        
        console.log('Current user:', user);
        console.log('Has token:', !!token);
        
        if (!token || !user) {
          console.log('No token or user found, redirecting to auth');
          this.$router.push('/auth');
          return;
        }
        
        const res = await api.get('/notes');
        this.notes = res.data;
        console.log('Fetched notes for user:', user.name, 'Notes count:', this.notes.length);
      } catch (error) {
        console.error('Error fetching notes:', error);
        console.error('Response data:', error.response?.data);
        console.error('Response status:', error.response?.status);
        
        if (error.response?.status === 401) {
          console.log('Unauthorized, clearing storage and redirecting');
          localStorage.removeItem('token');
          localStorage.removeItem('user');
          this.$router.push('/auth');
        }
      }
    },
    async clearAllNotes() {
      if (confirm('Are you sure you want to delete all notes?')) {
        try {
          const res = await api.get('/notes');
          const notes = res.data;
          await Promise.all(notes.map(note => api.delete(`/notes/${note.id}`)));
          this.getNotes();
          
          // Show success message
          ElMessage({
            message: 'All notes cleared successfully!',
            type: 'success',
            duration: 3000,
          });
        } catch (error) {
          console.error('Error clearing notes:', error);
          
          // Show error message
          ElMessage({
            message: 'Error clearing notes. Please try again.',
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
    async logout() {
      try {
        // Clear notes and user data
        this.notes = [];
        this.user = null;
        
        // Clear local storage
        localStorage.removeItem('token');
        localStorage.removeItem('user');
        
        // Show success message
        ElMessage({
          message: 'Logged out successfully!',
          type: 'success',
          duration: 3000,
        });
        
        // Redirect to auth page
        this.$router.push('/auth');
        
        // Optionally try server logout but don't block on failure
        try {
          await api.post('/auth/logout');
        } catch (error) {
          console.error('Server logout failed (but client logout succeeded):', error);
        }
      } catch (error) {
        console.error('Logout error:', error);
        
        // Show error message
        ElMessage({
          message: 'Error during logout. Please try again.',
          type: 'error',
          duration: 4000,
        });
      }
    },
  },
  mounted() {
    // Update user data from localStorage in case it changed
    this.user = JSON.parse(localStorage.getItem('user') || 'null');
    this.getNotes();
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
.container > div {
  transform: translateZ(0);
  transition: transform 0.3s ease;
}
.container > div:hover {
  transform: translateY(-4px) translateZ(10px);
}
</style>