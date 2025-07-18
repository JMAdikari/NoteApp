<template>
  <div>
    <h1>📝 My Notes</h1>
    <NoteForm @refresh="getNotes" />
    <NoteList :notes="notes" @refresh="getNotes" />
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
      const res = await api.get('/notes');
      this.notes = res.data;
    },
  },
  mounted() {
    this.getNotes();
  },
};
</script>
