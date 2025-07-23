import { api } from '@/axios';

export const authService = {
  // User login with email/password
  async login(email, password) {
    const response = await api.post('/auth/login', { email, password });
    return response.data;// Returns { token, user }
  },

  // User registration with validation
  async register(name, email, password, password_confirmation) {
    const response = await api.post('/auth/register', {
      name,
      email,
      password,
      password_confirmation
    });
    return response.data;// Returns { token, user }
  },

  // User logout (invalidates token on server)
  async logout() {
    const response = await api.post('/auth/logout');
    return response.data;
  }
};

export default authService;
