<template>
  <div class="min-h-screen relative overflow-hidden">
    <div class="absolute inset-0 bg-gradient-to-br from-blue-500 via-purple-500 to-pink-500 animate-gradient z-0"></div>
    
    <!-- Landing Page for Non-Authenticated Users -->
    <div v-if="!isAuthenticated" class="relative z-10 container mx-auto px-4 py-8">
      <div class="text-center mb-12">
        <h1 class="text-5xl md:text-6xl font-bold text-white drop-shadow-lg mb-6">
          📝 My Notes
        </h1>
        <p class="text-xl md:text-2xl text-white/90 max-w-3xl mx-auto mb-8">
          Your personal note-taking companion
        </p>
      </div>
      
      <!-- About Section -->
      <div class="bg-white backdrop-blur-sm rounded-lg shadow-2xl p-8 mb-8 max-w-4xl mx-auto">
        <h2 class="text-3xl font-bold text-gray-800 mb-6 text-center">Welcome to My Notes</h2>
        <div class="grid md:grid-cols-2 gap-8">
          <div class="space-y-6">
            <div class="flex items-start space-x-4">
              <div class="bg-blue-100 p-3 rounded-full">
                <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                </svg>
              </div>
              <div>
                <h3 class="text-xl font-semibold text-gray-800 mb-2">Create & Edit Notes</h3>
                <p class="text-gray-600">Easily create, edit, and organize your thoughts with our intuitive note-taking interface.</p>
              </div>
            </div>
            
            <div class="flex items-start space-x-4">
              <div class="bg-purple-100 p-3 rounded-full">
                <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
              </div>
              <div>
                <h3 class="text-xl font-semibold text-gray-800 mb-2">Secure & Private</h3>
                <p class="text-gray-600">Your notes are securely stored and only accessible to you with proper authentication.</p>
              </div>
            </div>
            
            <div class="flex items-start space-x-4">
              <div class="bg-green-100 p-3 rounded-full">
                <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                </svg>
              </div>
              <div>
                <h3 class="text-xl font-semibold text-gray-800 mb-2">Fast & Responsive</h3>
                <p class="text-gray-600">Lightning-fast performance with a beautiful, responsive design that works on all devices.</p>
              </div>
            </div>
          </div>
          
          <div class="flex flex-col justify-center items-center space-y-6">
            <div class="text-center">
              <h3 class="text-2xl font-bold text-gray-800 mb-4">Ready to get started?</h3>
              <p class="text-gray-600 mb-6">Join thousands of users who trust My Notes to organize their thoughts and ideas.</p>
              <button
                @click="navigateToAuth"
                class="bg-gradient-to-r from-blue-600 to-purple-600 text-white px-8 py-4 rounded-full font-bold text-lg hover:from-blue-700 hover:to-purple-700 transition-all transform hover:scale-105 shadow-lg hover:shadow-xl"
              >
                Let's Create Notes! 🚀
              </button>
            </div>
            <div class="text-center text-sm text-gray-500">
              <p>✨ Free to use • 🔒 Secure • 📱 Mobile friendly</p>
            </div>
          </div>
        </div>
      </div>
      
      <!-- Features Section -->
      <div class="bg-white backdrop-blur-sm rounded-lg shadow-xl p-6 max-w-4xl mx-auto">
        <h3 class="text-2xl font-bold text-gray-800 mb-4 text-center">Why Choose My Notes?</h3>
        <div class="grid md:grid-cols-3 gap-6 text-center">
          <div class="space-y-3">
            <div class="text-4xl">⚡</div>
            <h4 class="font-semibold text-gray-800">Lightning Fast</h4>
            <p class="text-sm text-gray-600">Instant note creation and editing with real-time updates</p>
          </div>
          <div class="space-y-3">
            <div class="text-4xl">🎨</div>
            <h4 class="font-semibold text-gray-800">Beautiful UI</h4>
            <p class="text-sm text-gray-600">Clean, modern interface designed for productivity</p>
          </div>
          <div class="space-y-3">
            <div class="text-4xl">🔄</div>
            <h4 class="font-semibold text-gray-800">Auto-Save</h4>
            <p class="text-sm text-gray-600">Never lose your work with automatic saving</p>
          </div>
        </div>
      </div>
    </div>
    
    <!-- Authenticated User Dashboard -->
    <div v-else class="relative z-10 container mx-auto px-4 py-8">
      <div class="text-center mb-12">
        <h1 class="text-4xl md:text-5xl font-bold text-white drop-shadow-lg mb-4">
          📝 My Notes
        </h1>
        <p class="text-lg md:text-xl text-white/80 max-w-2xl mx-auto">
          Welcome back, {{ user?.name || 'User' }}! Manage your notes below.
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
          class="bg-green-600 text-white px-6 py-3 rounded-full font-semibold hover:bg-green-700 transition-all transform hover:scale-105 shadow-lg"
          @click="navigateToProfile"
        >
          👤 Profile
        </button>
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
  computed: {
    isAuthenticated() {
      const token = localStorage.getItem('token');
      const user = localStorage.getItem('user');
      return !!(token && user);
    },
  },
  methods: {
    navigateToAuth() {
      this.$router.push('/auth');
    },
    navigateToProfile() {
      this.$router.push('/profile');
    },
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
    
    // Only fetch notes if user is authenticated
    if (this.isAuthenticated) {
      this.getNotes();
    }
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