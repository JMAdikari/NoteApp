import axios from 'axios';

const api = axios.create({
  baseURL: 'http://localhost:8000/api',// Laravel API endpoint
  // Use withCredentials only for session auth, false for token auth
  withCredentials: false,
  headers: {
    'Content-Type': 'application/json',
    'Accept': 'application/json',
  },
});

// Add Bearer token from localStorage to all requests
api.interceptors.request.use(config => {
  const token = localStorage.getItem('token');
  if (token) {
    config.headers.Authorization = `Bearer ${token}`;
  }
  
  return config;
}, error => Promise.reject(error));

// Handle 401 errors globally - clear token and redirect to login
api.interceptors.response.use(
  response => response,
  error => {
    if (error.response?.status === 401) {
      // Clear localStorage token and user data
      localStorage.removeItem('token');
      localStorage.removeItem('user');
      // Redirect to authentication page
      window.location.href = '/';
    }
    return Promise.reject(error);
  }
);

export { api };