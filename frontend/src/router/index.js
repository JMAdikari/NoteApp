import { createRouter, createWebHistory } from 'vue-router';
import HomePage from '@/views/HomePage.vue';
import EditNote from '@/components/EditNote.vue';

const routes = [
  {
    path: '/',
    name: 'Home',
    component: HomePage,
  },
  {
    path: '/notes/:id/edit',
    name: 'EditNote',
    component: EditNote,
  },
];

const router = createRouter({
  history: createWebHistory(),
  routes,
});

export default router;