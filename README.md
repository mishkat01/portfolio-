# Laravel Multi-Auth Admin Panel

A robust Laravel 12 application featuring a dual-authentication system (Admin & User), Role-Based Access Control (RBAC), and a modern Tailwind CSS UI.

## Features

*   **Multi-Authentication**: Separate login sessions and guards for Users (`web`) and Admins (`admin`).
*   **RBAC (Role-Based Access Control)**:
    *   Super Admin: Full access to everything.
    *   Roles: Create custom roles with specific permissions (e.g., `manage-users`, `view-dashboard`).
    *   Permissions: Granular control over admin capabilities.
*   **User Management**: CRUD operations for registered users.
*   **Admin Management**: accessible only to Super Admins.
*   **Modern UI**: Built with Blade and Tailwind CSS (Dark Mode supported).

## Requirements

*   PHP 8.2+
*   Composer
*   Node.js & NPM
*   MySQL or SQLite

## Installation Guide

Follow these steps to set up the project on your local machine.

### 1. Clone the Repository

```bash
git clone <repository-url>
cd <project-directory>
```

### 2. Install Dependencies

Install PHP and JavaScript dependencies:

```bash
composer install
npm install
```

### 3. Environment Setup

Copy the example environment file and configure your database settings:

```bash
cp .env.example .env
```

Open `.env` and set your database credentials:

```ini
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=your_database_name
DB_USERNAME=root
DB_PASSWORD=
```

### 4. Generate App Key

```bash
php artisan key:generate
```

### 5. Run Migrations & Seeders

This step is critical as it creates the database tables and **seeds the default Super Admin account**.

```bash
php artisan migrate --seed
```

> **Note:** If you want to refresh the database later, you can use `php artisan migrate:fresh --seed`.

### 6. Build Frontend Assets

```bash
npm run build
```

### 7. Run the Server

Start the Laravel development server:

```bash
php artisan serve
```

The application will be available at `http://localhost:8000`.

## Default Credentials

### Admin Panel
*   **URL**: 
*   **Email**: `admin@admin.com`
*   **Password**: `password`
*   **Role**: Super Admin

### User Panel
*   **URL**: 
*   **Register**: 

## Security

*   **Super Admin Restriction**: Only the Super Admin can create other Admins and manage Roles.
*   **Middleware**: Routes are protected by `auth:admin`, `superadmin`, and permission-based middleware (e.g., `permission:manage-users`).

## License

The Laravel framework is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).
