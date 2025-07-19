<template>
  <div class="min-h-screen relative overflow-hidden">
    <div class="absolute inset-0 bg-gradient-to-br from-gray-900 via-gray-800 to-black animate-gradient z-0"></div>
    
    <div class="relative z-10 container mx-auto px-4 py-8">
      <!-- Header -->
      <div class="text-center mb-8">
        <h1 class="text-4xl md:text-5xl font-bold text-white drop-shadow-lg mb-4">
          👤 Profile Settings
        </h1>
        <p class="text-lg md:text-xl text-white max-w-2xl mx-auto">
          Manage your account information and preferences
        </p>
      </div>

      <!-- Navigation -->
      <div class="mb-8 flex justify-center">
        <button
          @click="$router.push('/')"
          class="bg-gray-700/80 backdrop-blur-sm text-white px-6 py-3 rounded-full font-semibold hover:bg-gray-800/80 transition-all transform hover:scale-105 shadow-lg border border-gray-600"
        >
          ← Back to Notes
        </button>
      </div>

      <!-- Profile Content -->
      <div class="max-w-4xl mx-auto space-y-8">
        
        <!-- Profile Information Card -->
        <div class="bg-gray-800/90 backdrop-blur-sm rounded-lg shadow-2xl p-8 border border-gray-700">
          <h2 class="text-2xl font-bold text-white mb-6 flex items-center">
            <svg class="w-6 h-6 mr-3 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
            </svg>
            Profile Information
          </h2>
          
          <form @submit.prevent="updateProfile" class="space-y-6">
            <div class="grid md:grid-cols-2 gap-6">
              <div>
                <label for="name" class="block text-sm font-medium text-gray-300 mb-2">Full Name</label>
                <input
                  id="name"
                  v-model="profileForm.name"
                  type="text"
                  required
                  class="w-full px-4 py-3 border border-gray-600 bg-gray-700 text-white rounded-lg focus:ring-2 focus:ring-gray-500 focus:border-gray-500 transition-all placeholder-gray-400"
                  placeholder="Enter your full name"
                />
              </div>
              
              <div>
                <label for="email" class="block text-sm font-medium text-gray-300 mb-2">Email Address</label>
                <input
                  id="email"
                  v-model="profileForm.email"
                  type="email"
                  required
                  class="w-full px-4 py-3 border border-gray-600 bg-gray-700 text-white rounded-lg focus:ring-2 focus:ring-gray-500 focus:border-gray-500 transition-all placeholder-gray-400"
                  placeholder="Enter your email address"
                />
              </div>
            </div>
            
            <div class="flex justify-end">
              <button
                type="submit"
                :disabled="updating"
                class="bg-gradient-to-r from-gray-600 to-gray-800 text-white px-8 py-3 rounded-lg font-semibold hover:from-gray-700 hover:to-gray-900 transition-all transform hover:scale-105 shadow-lg disabled:opacity-50 disabled:cursor-not-allowed border border-gray-600"
              >
                {{ updating ? 'Updating...' : 'Update Profile' }}
              </button>
            </div>
          </form>
        </div>

        <!-- Account Statistics -->
        <div class="bg-gray-800/90 backdrop-blur-sm rounded-lg shadow-2xl p-8 border border-gray-700">
          <h2 class="text-2xl font-bold text-white mb-6 flex items-center">
            <svg class="w-6 h-6 mr-3 text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
            </svg>
            Account Statistics
          </h2>
          
          <div class="grid md:grid-cols-3 gap-6">
            <div class="text-center p-6 bg-gradient-to-br from-blue-900/80 to-blue-800/80 rounded-lg border border-blue-700">
              <div class="text-3xl font-bold text-blue-400 mb-2">{{ userProfile.notes_count || 0 }}</div>
              <div class="text-sm text-gray-300">Total Notes</div>
            </div>
            
            <div class="text-center p-6 bg-gradient-to-br from-green-900/80 to-green-800/80 rounded-lg border border-green-700">
              <div class="text-3xl font-bold text-green-400 mb-2">{{ memberSince }}</div>
              <div class="text-sm text-gray-300">Member Since</div>
            </div>
            
            <div class="text-center p-6 bg-gradient-to-br from-purple-900/80 to-purple-800/80 rounded-lg border border-purple-700">
              <div class="text-3xl font-bold text-purple-400 mb-2">{{ lastUpdated }}</div>
              <div class="text-sm text-gray-300">Last Updated</div>
            </div>
          </div>
        </div>

        <!-- Change Password Card -->
        <div class="bg-gray-800/90 backdrop-blur-sm rounded-lg shadow-2xl p-8 border border-gray-700">
          <h2 class="text-2xl font-bold text-white mb-6 flex items-center">
            <svg class="w-6 h-6 mr-3 text-orange-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
            </svg>
            Change Password
          </h2>
          
          <form @submit.prevent="changePassword" class="space-y-6">
            <div class="grid md:grid-cols-1 gap-6 max-w-md">
              <div>
                <label for="current_password" class="block text-sm font-medium text-gray-300 mb-2">Current Password</label>
                <input
                  id="current_password"
                  v-model="passwordForm.current_password"
                  type="password"
                  required
                  class="w-full px-4 py-3 border border-gray-600 bg-gray-700 text-white rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-orange-500 transition-all placeholder-gray-400"
                  placeholder="Enter current password"
                />
              </div>
              
              <div>
                <label for="new_password" class="block text-sm font-medium text-gray-300 mb-2">New Password</label>
                <input
                  id="new_password"
                  v-model="passwordForm.new_password"
                  type="password"
                  required
                  minlength="8"
                  class="w-full px-4 py-3 border border-gray-600 bg-gray-700 text-white rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-orange-500 transition-all placeholder-gray-400"
                  placeholder="Enter new password"
                />
              </div>
              
              <div>
                <label for="new_password_confirmation" class="block text-sm font-medium text-gray-300 mb-2">Confirm New Password</label>
                <input
                  id="new_password_confirmation"
                  v-model="passwordForm.new_password_confirmation"
                  type="password"
                  required
                  minlength="8"
                  class="w-full px-4 py-3 border border-gray-600 bg-gray-700 text-white rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-orange-500 transition-all placeholder-gray-400"
                  placeholder="Confirm new password"
                />
              </div>
            </div>
            
            <div class="flex justify-start">
              <button
                type="submit"
                :disabled="changingPassword"
                class="bg-gradient-to-r from-orange-600 to-red-600 text-white px-8 py-3 rounded-lg font-semibold hover:from-orange-700 hover:to-red-700 transition-all transform hover:scale-105 shadow-lg disabled:opacity-50 disabled:cursor-not-allowed border border-orange-600"
              >
                {{ changingPassword ? 'Changing...' : 'Change Password' }}
              </button>
            </div>
          </form>
        </div>

        <!-- Danger Zone -->
        <div class="bg-gray-800/90 backdrop-blur-sm rounded-lg shadow-2xl p-8 border-l-4 border-red-500 border border-gray-700">
          <h2 class="text-2xl font-bold text-red-400 mb-6 flex items-center">
            <svg class="w-6 h-6 mr-3 text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z"></path>
            </svg>
            Danger Zone
          </h2>
          
          <div class="bg-red-900/30 p-6 rounded-lg border border-red-700">
            <h3 class="text-lg font-semibold text-red-400 mb-2">Delete Account</h3>
            <p class="text-red-300 mb-4">
              Permanently delete your account and all associated data. This action cannot be undone.
            </p>
            
            <button
              @click="showDeleteConfirmation = true"
              class="bg-red-600 text-white px-6 py-2 rounded-lg font-semibold hover:bg-red-700 transition-all transform hover:scale-105 shadow-lg border border-red-500"
            >
              Delete Account
            </button>
          </div>
        </div>
      </div>
    </div>

    <!-- Delete Confirmation Modal -->
    <div v-if="showDeleteConfirmation" class="fixed inset-0 bg-black/50 flex items-center justify-center z-50 p-4">
      <div class="bg-gray-800 rounded-lg shadow-2xl p-8 max-w-md w-full border border-gray-700">
        <h3 class="text-xl font-bold text-red-400 mb-4">Confirm Account Deletion</h3>
        <p class="text-gray-300 mb-6">
          Are you sure you want to delete your account? This will permanently remove all your notes and data.
        </p>
        
        <div class="mb-6">
          <label for="delete_password" class="block text-sm font-medium text-gray-300 mb-2">Enter your password to confirm</label>
          <input
            id="delete_password"
            v-model="deleteForm.password"
            type="password"
            required
            class="w-full px-4 py-3 border border-gray-600 bg-gray-700 text-white rounded-lg focus:ring-2 focus:ring-red-500 focus:border-red-500 transition-all placeholder-gray-400"
            placeholder="Enter your password"
          />
        </div>
        
        <div class="flex space-x-4">
          <button
            @click="showDeleteConfirmation = false; deleteForm.password = ''"
            class="flex-1 bg-gray-600 text-gray-300 px-4 py-2 rounded-lg font-semibold hover:bg-gray-700 transition-all border border-gray-500"
          >
            Cancel
          </button>
          <button
            @click="deleteAccount"
            :disabled="deleting || !deleteForm.password"
            class="flex-1 bg-red-600 text-white px-4 py-2 rounded-lg font-semibold hover:bg-red-700 transition-all disabled:opacity-50 disabled:cursor-not-allowed border border-red-500"
          >
            {{ deleting ? 'Deleting...' : 'Delete Account' }}
          </button>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import { api } from '@/axios';
import { ElMessage } from 'element-plus';

export default {
  name: 'ProfilePage',
  data() {
    return {
      userProfile: {},
      profileForm: {
        name: '',
        email: ''
      },
      passwordForm: {
        current_password: '',
        new_password: '',
        new_password_confirmation: ''
      },
      deleteForm: {
        password: ''
      },
      updating: false,
      changingPassword: false,
      deleting: false,
      showDeleteConfirmation: false
    };
  },
  computed: {
    memberSince() {
      if (!this.userProfile.created_at) return '-';
      return new Date(this.userProfile.created_at).getFullYear();
    },
    lastUpdated() {
      if (!this.userProfile.updated_at) return '-';
      const date = new Date(this.userProfile.updated_at);
      return date.toLocaleDateString();
    }
  },
  methods: {
    async fetchProfile() {
      try {
        const response = await api.get('/user/profile');
        this.userProfile = response.data.user;
        this.profileForm.name = this.userProfile.name;
        this.profileForm.email = this.userProfile.email;
      } catch (error) {
        console.error('Error fetching profile:', error);
        ElMessage({
          message: 'Error loading profile information',
          type: 'error',
          duration: 4000,
        });
        
        if (error.response?.status === 401) {
          localStorage.removeItem('token');
          localStorage.removeItem('user');
          this.$router.push('/auth');
        }
      }
    },
    
    async updateProfile() {
      if (this.updating) return;
      
      this.updating = true;
      try {
        const response = await api.put('/user/profile', this.profileForm);
        this.userProfile = response.data.user;
        
        // Update localStorage with new user data
        localStorage.setItem('user', JSON.stringify(this.userProfile));
        
        ElMessage({
          message: 'Profile updated successfully!',
          type: 'success',
          duration: 3000,
        });
      } catch (error) {
        console.error('Error updating profile:', error);
        
        if (error.response?.data?.errors) {
          const errors = error.response.data.errors;
          const errorMessages = Object.values(errors).flat().join(', ');
          ElMessage({
            message: `Error: ${errorMessages}`,
            type: 'error',
            duration: 4000,
          });
        } else {
          ElMessage({
            message: 'Error updating profile. Please try again.',
            type: 'error',
            duration: 4000,
          });
        }
        
        if (error.response?.status === 401) {
          localStorage.removeItem('token');
          localStorage.removeItem('user');
          this.$router.push('/auth');
        }
      } finally {
        this.updating = false;
      }
    },
    
    async changePassword() {
      if (this.changingPassword) return;
      
      if (this.passwordForm.new_password !== this.passwordForm.new_password_confirmation) {
        ElMessage({
          message: 'New passwords do not match',
          type: 'error',
          duration: 4000,
        });
        return;
      }
      
      this.changingPassword = true;
      try {
        await api.put('/user/password', this.passwordForm);
        
        // Clear the form
        this.passwordForm = {
          current_password: '',
          new_password: '',
          new_password_confirmation: ''
        };
        
        ElMessage({
          message: 'Password changed successfully!',
          type: 'success',
          duration: 3000,
        });
      } catch (error) {
        console.error('Error changing password:', error);
        
        if (error.response?.data?.message) {
          ElMessage({
            message: error.response.data.message,
            type: 'error',
            duration: 4000,
          });
        } else if (error.response?.data?.errors) {
          const errors = error.response.data.errors;
          const errorMessages = Object.values(errors).flat().join(', ');
          ElMessage({
            message: `Error: ${errorMessages}`,
            type: 'error',
            duration: 4000,
          });
        } else {
          ElMessage({
            message: 'Error changing password. Please try again.',
            type: 'error',
            duration: 4000,
          });
        }
        
        if (error.response?.status === 401) {
          localStorage.removeItem('token');
          localStorage.removeItem('user');
          this.$router.push('/auth');
        }
      } finally {
        this.changingPassword = false;
      }
    },
    
    async deleteAccount() {
      if (this.deleting) return;
      
      this.deleting = true;
      try {
        await api.delete('/user/account', {
          data: { password: this.deleteForm.password }
        });
        
        // Clear all local storage
        localStorage.removeItem('token');
        localStorage.removeItem('user');
        
        ElMessage({
          message: 'Account deleted successfully',
          type: 'success',
          duration: 3000,
        });
        
        this.$router.push('/auth');
      } catch (error) {
        console.error('Error deleting account:', error);
        
        if (error.response?.data?.message) {
          ElMessage({
            message: error.response.data.message,
            type: 'error',
            duration: 4000,
          });
        } else {
          ElMessage({
            message: 'Error deleting account. Please try again.',
            type: 'error',
            duration: 4000,
          });
        }
      } finally {
        this.deleting = false;
        this.showDeleteConfirmation = false;
        this.deleteForm.password = '';
      }
    }
  },
  
  mounted() {
    // Check if user is authenticated
    const token = localStorage.getItem('token');
    const user = localStorage.getItem('user');
    
    if (!token || !user) {
      this.$router.push('/auth');
      return;
    }
    
    this.fetchProfile();
  }
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
  transform: translateY(-2px) translateZ(5px);
}
</style>
