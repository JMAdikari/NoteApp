// Token Authentication Test Utility
// This file helps verify that localStorage token authentication is working correctly

export const tokenAuth = {
  // Get the current token from localStorage
  getToken() {
    return localStorage.getItem('token');
  },

  // Get the current user from localStorage
  getUser() {
    const userStr = localStorage.getItem('user');
    return userStr ? JSON.parse(userStr) : null;
  },

  // Check if user is authenticated
  isAuthenticated() {
    return !!this.getToken() && !!this.getUser();
  },

  // Save authentication data
  saveAuth(token, user) {
    localStorage.setItem('token', token);
    localStorage.setItem('user', JSON.stringify(user));
  },

  // Clear authentication data
  clearAuth() {
    localStorage.removeItem('token');
    localStorage.removeItem('user');
  },

  // Get auth headers for manual requests
  getAuthHeaders() {
    const token = this.getToken();
    return token ? { Authorization: `Bearer ${token}` } : {};
  }
};
