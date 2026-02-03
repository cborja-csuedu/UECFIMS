# UECFIMS - Role-Based Organization Structure

This document outlines how the UECFIMS application is organized by user roles.

## Directory Structure by Role

### Controllers (`app/Http/Controllers/`)

```
Controllers/
├── Auth/                          # Authentication controllers (shared)
│   ├── LoginController.php
│   └── RegisterController.php
├── Admin/                         # Admin-only controllers
│   └── DashboardController.php
├── Secretary/                     # Secretary-only controllers
│   ├── DashboardController.php
│   └── MemberController.php
├── User/                          # Regular user controllers
│   ├── DashboardController.php
│   └── MemberController.php
└── Controller.php                 # Base controller
```

### Views (`resources/views/`)

```
views/
├── auth/                          # Authentication views (shared)
├── layouts/                       # Layout templates (shared)
├── admin/                         # Admin-specific views
│   └── dashboard.blade.php
├── secretary/                     # Secretary-specific views
│   └── dashboard.blade.php
├── user/                          # User-specific views
│   └── dashboard.blade.php
├── members/                       # Member registration views (shared)
│   └── create.blade.php
├── dashboard.blade.php            # Legacy unified dashboard
└── welcome.blade.php              # Public welcome page
```

### Seeders (`database/seeders/`)

```
seeders/
├── DatabaseSeeder.php             # Main seeder (calls all role seeders)
├── AdminSeeder.php                # Seeds admin users
├── SecretarySeeder.php            # Seeds secretary users
└── UserSeeder.php                 # Seeds regular users
```

### Middleware (`app/Http/Middleware/`)

```
Middleware/
├── IsAdmin.php                    # Verify admin role
├── IsSecretary.php                # Verify secretary role
└── IsUser.php                     # Verify user role
```

### Routes (`routes/`)

```
routes/
└── web.php                        # All routes (role-grouped)
```

## User Roles

### 1. Admin (`admin@example.com`)
- **Namespace**: `App\Http\Controllers\Admin`
- **Views**: `resources/views/admin/`
- **Middleware**: `admin`
- **Routes Prefix**: `/admin`
- **Permissions**:
  - View all member registrations
  - View system statistics
  - Manage users
  - System configuration

### 2. Secretary (`secretary1@example.com`, `secretary2@example.com`)
- **Namespace**: `App\Http\Controllers\Secretary`
- **Views**: `resources/views/secretary/`
- **Middleware**: `secretary`
- **Routes Prefix**: `/secretary`
- **Permissions**:
  - Secretary 1: Verify member registrations, notify Secretary 2 and Admin
  - View all member registrations
  - Manage secretary-level tasks

### 3. User (`user@example.com`)
- **Namespace**: `App\Http\Controllers\User`
- **Views**: `resources/views/user/`
- **Middleware**: `user`
- **Routes Prefix**: `/user`
- **Permissions**:
  - Register new members
  - View own registrations
  - Submit forms

## Route Structure

```php
// Public routes
GET  /                  → Welcome page
GET  /login            → Login form
POST /login            → Process login
GET  /register         → Register form
POST /register         → Process registration
POST /logout           → Process logout

// Protected routes (all require authentication)
// Admin Routes
GET  /admin/dashboard                    → Admin dashboard
// Secretary Routes
GET  /secretary/dashboard                → Secretary dashboard
POST /secretary/members/{id}/verify      → Verify member
// User Routes
GET  /user/dashboard                     → User dashboard
GET  /user/members/create                → Member registration form
POST /user/members                       → Store member registration

// General (accessible by users)
GET  /members/create                     → Member registration form
POST /members                            → Store member registration
POST /members/{member}/verify            → Verify member (secretary only)
```

## Notification Flow

### Member Registration Submission (User → All Secretaries)
1. User submits member registration via `/members` POST
2. `UserMemberController@store` creates notification for **all secretaries**
3. Notification appears in secretary dashboards

### Member Verification (Secretary 1 → Secretary 2 & Admin)
1. Secretary 1 clicks verify button
2. `SecretaryMemberController@verify` updates status to 'approved'
3. Notifications sent to Secretary 2 and Admin

## Key Features

### Role-Based Access Control
- Each role has dedicated controllers
- Middleware enforces role-based route access
- Views are organized by role for clarity

### Organized Seeders
- `AdminSeeder` → creates admin user(s)
- `SecretarySeeder` → creates secretary user(s)
- `UserSeeder` → creates regular user(s)
- All called from `DatabaseSeeder`

### Notification System
- All secretaries receive member registration notifications
- Admin and Secretary 2 notified when Secretary 1 verifies a member
- Notifications stored in database with user relationship

## Setup Instructions

### 1. Fresh Installation
```bash
php artisan migrate
php artisan db:seed
```

### 2. Default Users (After Seeding)
- **Admin**: admin@example.com / password
- **Secretary 1**: secretary1@example.com / password
- **Secretary 2**: secretary2@example.com / password
- **User**: user@example.com / password

### 3. Test Role-Based Access
1. Log in as different roles
2. Verify you're redirected to correct dashboard
3. Check that unauthorized access is blocked

## Development Guidelines

### Adding a New Feature for a Specific Role
1. Create controller in `app/Http/Controllers/{Role}/`
2. Create/update views in `resources/views/{role}/`
3. Add routes in `routes/web.php` under role group
4. Use role middleware on routes
5. Update documentation

### Adding a New User Role
1. Add role value to User model
2. Create `{Role}Controller` in `app/Http/Controllers/{Role}/`
3. Create `{Role}Seeder` in `database/seeders/`
4. Create `Is{Role}` middleware
5. Register middleware in `bootstrap/app.php`
6. Add routes with middleware protection
7. Create views in `resources/views/{role}/`
8. Update routes to include new role group

## Security Considerations

- All protected routes use authentication middleware
- Role-based routes use specific role middleware
- Email hardcoding has been replaced with role-based queries
- Notifications sent based on user role, not email
- Each role can only access their designated routes
