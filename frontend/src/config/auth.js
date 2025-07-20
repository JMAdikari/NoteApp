// Authentication configuration
export const AUTH_CONFIG = {
  // Choose your authentication strategy:
  // 'token' - Stateless authentication using Bearer tokens (recommended for APIs)
  // 'session' - Stateful authentication using cookies and sessions
  strategy: 'token',
  
  // API endpoints
  endpoints: {
    login: '/auth/login',
    logout: '/auth/logout',
    register: '/auth/register',
  }
};
