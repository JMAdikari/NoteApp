<template>
  <div class="min-h-screen relative overflow-hidden">
    <div class="absolute inset-0 bg-gradient-to-br from-gray-900 via-gray-800 to-black animate-gradient z-0"></div>
    <div class="relative z-10 container mx-auto px-4 py-8">
      <div class="text-center mb-12">
        <h1 class="text-4xl md:text-5xl font-bold text-white drop-shadow-lg mb-4">
          📝 My Notes
        </h1>
        <p class="text-lg md:text-xl text-white/80 max-w-2xl mx-auto">
          Sign in or create an account to start managing your notes.
        </p>
      </div>
      <div class="bg-gray-800/90 backdrop-blur-sm rounded-lg shadow-2xl p-6 max-w-md mx-auto border border-gray-700">
        <h2 class="text-2xl font-semibold text-white mb-4">{{ isLogin ? 'Login' : 'Register' }}</h2>
        <form @submit.prevent="submit" class="space-y-4">
          <div v-if="!isLogin">
            <label class="block text-gray-300 font-medium">Name</label>
            <input
              v-model="form.name"
              type="text"
              class="w-full p-2 border border-gray-600 bg-gray-700 text-white rounded-md focus:outline-none focus:ring-2 focus:ring-gray-500 focus:border-gray-500"
              placeholder="Enter your name"
              required
            />
          </div>
          <div>
            <label class="block text-gray-300 font-medium">Email</label>
            <input
              v-model="form.email"
              type="email"
              class="w-full p-2 border border-gray-600 bg-gray-700 text-white rounded-md focus:outline-none focus:ring-2 focus:ring-gray-500 focus:border-gray-500"
              placeholder="Enter your email"
              required
            />
          </div>
          <div>
            <label class="block text-gray-300 font-medium">Password</label>
            <input
              v-model="form.password"
              type="password"
              class="w-full p-2 border border-gray-600 bg-gray-700 text-white rounded-md focus:outline-none focus:ring-2 focus:ring-gray-500 focus:border-gray-500"
              placeholder="Enter your password"
              required
            />
          </div>
          <div v-if="!isLogin">
            <label class="block text-gray-300 font-medium">Confirm Password</label>
            <input
              v-model="form.password_confirmation"
              type="password"
              class="w-full p-2 border border-gray-600 bg-gray-700 text-white rounded-md focus:outline-none focus:ring-2 focus:ring-gray-500 focus:border-gray-500"
              placeholder="Confirm your password"
              required
            />
          </div>
          <div v-if="error" class="text-red-600 text-sm">{{ error }}</div>
          <button
            type="submit"
            class="w-full bg-gray-700 text-white px-4 py-2 rounded-full font-semibold hover:bg-gray-800 transition-all transform hover:scale-105 shadow border border-gray-600"
          >
            {{ isLogin ? 'Login' : 'Register' }}
          </button>
          <button
            type="button"
            @click="isLogin = !isLogin"
            class="w-full text-gray-300 text-sm hover:text-gray-100"
          >
            {{ isLogin ? 'Need an account? Register' : 'Already have an account? Login' }}
          </button>
        </form>
      </div>
    </div>
  </div>
</template>

<script>
import { api, sanctum } from '@/axios';
import { ElMessage } from 'element-plus';

export default {
  data() {
    return {
      isLogin: true,
      form: {
        name: '',
        email: '',
        password: '',
        password_confirmation: '',
      },
      error: null,
    };
  },
  methods: {
    async submit() {
      try {
        this.error = null;
        
        // Clear any existing user data first
        localStorage.removeItem('token');
        localStorage.removeItem('user');
        
        // Fetch CSRF token using the sanctum instance
        await sanctum.get('/sanctum/csrf-cookie');
        
        // Small delay to ensure cookie is set
        await new Promise(resolve => setTimeout(resolve, 200));
        
        const endpoint = this.isLogin ? 'auth/login' : 'auth/register';
        const response = await api.post(endpoint, this.form);
        
        console.log('Login/Register successful for user:', response.data.user);
        
        // Set new user data
        localStorage.setItem('token', response.data.token);
        localStorage.setItem('user', JSON.stringify(response.data.user));
        
        // Show success message
        ElMessage({
          message: this.isLogin ? 'Login successful!' : 'Registration successful!',
          type: 'success',
          duration: 3000,
        });
        
        this.$router.push('/');
      } catch (error) {
        console.error('Auth error:', error);
        this.error = error.response?.data?.message || 'An error occurred';
        
        if (error.response?.status === 419) {
          this.error = 'CSRF token mismatch. Please try again.';
        } else if (error.response?.status === 404) {
          this.error = 'CSRF endpoint not found. Please check backend configuration.';
        } else if (error.response?.status === 422) {
          // Handle validation errors
          const errors = error.response.data.errors;
          if (errors) {
            this.error = Object.values(errors).flat().join(', ');
          }
        }
        
        // Show error message
        ElMessage({
          message: this.error,
          type: 'error',
          duration: 4000,
        });
      }
    },
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