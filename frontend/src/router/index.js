import { createRouter, createWebHistory } from 'vue-router';
import HomePage from '@/views/HomePage.vue';
import EditNote from '@/components/EditNote.vue';
import AuthPage from '@/components/AuthPage.vue';

const routes = [
  {
    path: '/',
    name: 'Home',
    component: HomePage,
    meta: { requiresAuth: true },
  },
  {
    path: '/auth',
    name: 'Auth',
    component: AuthPage,
  },
  {
    path: '/notes/:id/edit',
    name: 'EditNote',
    component: EditNote,
    meta: { requiresAuth: true },
  },
];

const router = createRouter({
  history: createWebHistory(),
  routes,
});

router.beforeEach((to, from, next) => {
  const token = localStorage.getItem('token');
  const user = localStorage.getItem('user');
  
  if (to.meta.requiresAuth && (!token || !user)) {
    // Clear any invalid partial data
    localStorage.removeItem('token');
    localStorage.removeItem('user');
    next('/auth');
  } else if (to.name === 'Auth' && token && user) {
    next('/');
  } else {
    next();
  }
});

export default router;