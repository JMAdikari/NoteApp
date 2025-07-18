import { api } from '@/axios';

export const authService = {
  async getCsrfToken() {
    await api.get('http://localhost:8000/sanctum/csrf-cookie');
  },

  async login(email, password) {
    await this.getCsrfToken();
    const response = await api.post('/auth/login', { email, password });
    return response.data;
  },

  async register(name, email, password, password_confirmation) {
    await this.getCsrfToken();
    const response = await api.post('/auth/register', {
      name,
      email,
      password,
      password_confirmation
    });
    return response.data;
  },

  async logout() {
    const response = await api.post('/auth/logout');
    return response.data;
  }
};

export default authService;
