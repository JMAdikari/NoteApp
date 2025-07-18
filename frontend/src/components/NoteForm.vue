<template>
  <form @submit.prevent="handleSubmit">
    <input v-model="title" placeholder="Title" required />
    <textarea v-model="body" placeholder="Note body (optional)"></textarea>
    <button type="submit">{{ editing ? 'Update' : 'Add' }} Note</button>
    <button v-if="editing" @click.prevent="resetForm">Cancel</button>
  </form>
</template>

<script>
import api from '@/axios';

export default {
  props: ['editNote'],
  data() {
    return {
      title: '',
      body: '',
      id: null,
      editing: false,
    };
  },
  watch: {
    editNote(note) {
      if (note) {
        this.title = note.title;
        this.body = note.body;
        this.id = note.id;
        this.editing = true;
      }
    },
  },
  methods: {
    async handleSubmit() {
      if (this.editing) {
        await api.put(`/notes/${this.id}`, { title: this.title, body: this.body });
      } else {
        await api.post('/notes', { title: this.title, body: this.body });
      }
      this.resetForm();
      this.$emit('refresh');
    },
    resetForm() {
      this.title = '';
      this.body = '';
      this.id = null;
      this.editing = false;
    },
  },
};
</script>
