# NoteApp 📝

A full-stack note-taking application built with Laravel (Backend) and Vue.js (Frontend). This application allows users to create, read, update and delete personal notes with user authentication and profile management.

## 🚀 Features

- **User Authentication**: Register, login, and logout functionality
- **Notes Management**: Create, read, update, and delete notes
- **User Profile**: View and update profile information
- **Password Management**: Change user password
- **Account Management**: Delete user account
- **Responsive Design**: Built with Tailwind CSS and Element Plus
- **API-based Architecture**: RESTful API backend with Vue.js frontend

## 🛠️ Technology Stack

### Backend
- **Framework**: Laravel 10.x
- **Language**: PHP 8.1+
- **Database**: MySQL/MariaDB
- **Authentication**: Laravel Sanctum
- **API**: RESTful API

### Frontend
- **Framework**: Vue.js 3
- **Language**: JavaScript
- **UI Library**: Element Plus
- **Styling**: Tailwind CSS
- **HTTP Client**: Axios
- **Router**: Vue Router 4

## 📁 Project Structure

```
NoteApp/
├── Backend/                 # Laravel API Backend
│   ├── app/
│   │   ├── Http/
│   │   │   └── Controllers/
│   │   │       └── Api/
│   │   │           ├── AuthController.php
│   │   │           ├── NoteController.php
│   │   │           └── UserController.php
│   │   └── Models/
│   │       ├── Note.php
│   │       └── User.php
│   ├── database/
│   │   └── migrations/
│   ├── routes/
│   │   └── api.php
│   └── ...
└── frontend/               # Vue.js Frontend
    ├── src/
    │   ├── components/
    │   ├── views/
    │   ├── router/
    │   ├── services/
    │   └── utils/
    ├── public/
    └── ...
```

## 🚀 Installation & Setup

### Prerequisites
- PHP 8.1 or higher
- Composer
- Node.js 16+ and npm
- MySQL/MariaDB
- XAMPP/WAMP/MAMP (for local development)

### Backend Setup (Laravel)

1. **Navigate to Backend directory**
   ```bash
   cd Backend
   ```

2. **Install PHP dependencies**
   ```bash
   composer install
   ```

3. **Environment Setup**
   ```bash
   cp .env.example .env
   ```

4. **Configure Database**
   Edit `.env` file with your database credentials:
   ```env
   DB_CONNECTION=mysql
   DB_HOST=127.0.0.1
   DB_PORT=3306
   DB_DATABASE=noteapp
   DB_USERNAME=your_username
   DB_PASSWORD=your_password
   ```

5. **Generate Application Key**
   ```bash
   php artisan key:generate
   ```

6. **Run Database Migrations**
   ```bash
   php artisan migrate
   ```

7. **Start Laravel Development Server**
   ```bash
   php artisan serve
   ```
   Backend will be available at `http://localhost:8000`

### Frontend Setup (Vue.js)

1. **Navigate to Frontend directory**
   ```bash
   cd frontend
   ```

2. **Install Node dependencies**
   ```bash
   npm install
   ```

3. **Configure API Endpoint**
   Update the API base URL in your axios configuration file to match your Laravel backend URL.

4. **Start Development Server**
   ```bash
   npm run serve
   ```
   Frontend will be available at `http://localhost:8080`

## 📊 Database Schema

### Users Table
- `id` - Primary key
- `name` - User's full name
- `email` - User's email (unique)
- `password` - Hashed password
- `created_at` - Registration timestamp
- `updated_at` - Last update timestamp

### Notes Table
- `id` - Primary key
- `user_id` - Foreign key to users table
- `title` - Note title (max 255 characters)
- `body` - Note content (text)
- `created_at` - Creation timestamp
- `updated_at` - Last update timestamp

## 🔌 API Endpoints

### Authentication
- `POST /api/auth/register` - User registration
- `POST /api/auth/login` - User login
- `POST /api/auth/logout` - User logout (requires authentication)

### Notes Management
- `GET /api/notes` - Get all user notes
- `POST /api/notes` - Create new note
- `GET /api/notes/{id}` - Get specific note
- `PUT /api/notes/{id}` - Update note
- `DELETE /api/notes/{id}` - Delete note

### User Profile
- `GET /api/user/profile` - Get user profile
- `PUT /api/user/profile` - Update user profile
- `PUT /api/user/password` - Change password
- `DELETE /api/user/account` - Delete user account

## 🎨 Frontend Pages

- **Home Page**: Landing page with notes listing
- **Authentication Page**: Login/Register forms
- **Profile Page**: User profile management
- **Edit Note**: Note creation and editing interface

## 🛡️ Security Features

- **Laravel Sanctum**: Token-based API authentication
- **Password Hashing**: Secure password storage using Laravel's Hash facade
- **CSRF Protection**: Cross-site request forgery protection
- **Input Validation**: Server-side validation for all API endpoints
- **Route Protection**: Authentication middleware for protected routes


## 📧 Contact

- **GitHub**: [@JMAdikari](https://github.com/JMAdikari)
- **Repository**: [NoteApp](https://github.com/JMAdikari/NoteApp)

