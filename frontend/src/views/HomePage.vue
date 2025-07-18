<template>
  <div class="min-h-screen relative overflow-hidden">
    <!-- 3D Background -->
    <div class="absolute inset-0 bg-gradient-to-br from-blue-500 via-purple-500 to-pink-500 animate-gradient z-0"></div>
    <div class="relative z-10 container mx-auto px-4 py-8">
      <!-- Header Section -->
      <div class="text-center mb-12">
        <h1 class="text-4xl md:text-5xl font-bold text-white drop-shadow-lg mb-4">
          📝 My Notes
        </h1>
        <p class="text-lg md:text-xl text-white/80 max-w-2xl mx-auto">
          Capture your thoughts, ideas, and tasks with ease. Organize your notes in a sleek, modern interface with a vibrant 3D background.
        </p>
      </div>
      
      <!-- Note Form -->
      <div class="bg-white/90 backdrop-blur-sm rounded-lg shadow-2xl p-6 mb-8 max-w-3xl mx-auto">
        <NoteForm @refresh="getNotes" />
      </div>
      
      <!-- Note List -->
      <div class="bg-white/90 backdrop-blur-sm rounded-lg shadow-2xl p-6 max-w-3xl mx-auto">
        <NoteList :notes="notes" @refresh="getNotes" />
      </div>
      
      <!-- Action Buttons -->
      <div class="flex justify-center gap-4 mt-8">
        <button 
          class="bg-blue-600 text-white px-6 py-3 rounded-full font-semibold hover:bg-blue-700 transition-all transform hover:scale-105 shadow-lg"
          @click="getNotes"
        >
          Refresh Notes
        </button>
        <button 
          class="bg-purple-600 text-white px-6 py-3 rounded-full font-semibold hover:bg-purple-700 transition-all transform hover:scale-105 shadow-lg"
          @click="clearAllNotes"
        >
          Clear All Notes
        </button>
      </div>
    </div>
  </div>
</template>

<script>
import NoteForm from '@/components/NoteForm.vue';
import NoteList from '@/components/NoteList.vue';
import api from '@/axios.js';

export default {
  components: { NoteForm, NoteList },
  data() {
    return {
      notes: [],
    };
  },
  methods: {
    async getNotes() {
      try {
        const res = await api.get('/notes');
        this.notes = res.data;
      } catch (error) {
        console.error('Error fetching notes:', error);
      }
    },
    async clearAllNotes() {
      if (confirm('Are you sure you want to delete all notes?')) {
        try {
          await Promise.all(this.notes.map(note => api.delete(`/notes/${note.id}`)));
          this.getNotes();
        } catch (error) {
          console.error('Error clearing notes:', error);
        }
      }
    },
  },
  mounted() {
    this.getNotes();
  },
};
</script>

<style scoped>
/* 3D Background Animation */
@keyframes gradient {
  0% {
    background-position: 0% 50%;
  }
  50% {
    background-position: 100% 50%;
  }
  100% {
    background-position: 0% 50%;
  }
}

.animate-gradient {
  background-size: 200% 200%;
  animation: gradient 15s ease infinite;
}

/* 3D Effect for Containers */
.container > div {
  transform: translateZ(0);
  transition: transform 0.3s ease;
}

.container > div:hover {
  transform: translateY(-4px) translateZ(10px);
}
</style>