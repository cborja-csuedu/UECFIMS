# UECFIMS - Complete Role-Based Directory Structure

## Project Organization Tree

```
UECFIMS/
│
├── 📁 app/
│   ├── 📁 Http/
│   │   ├── 📁 Controllers/
│   │   │   ├── Controller.php                 # Base controller
│   │   │   │
│   │   │   ├── 📁 Auth/                       # 🔐 Shared Authentication
│   │   │   │   ├── LoginController.php
│   │   │   │   └── RegisterController.php
│   │   │   │
│   │   │   ├── 📁 Admin/                      # 👨‍💼 Admin-Only Features
│   │   │   │   └── DashboardController.php    (View stats, manage system)
│   │   │   │
│   │   │   ├── 📁 Secretary/                  # 👩‍💻 Secretary-Only Features
│   │   │   │   ├── DashboardController.php    (View submissions)
│   │   │   │   └── MemberController.php       (Verify members)
│   │   │   │
│   │   │   └── 📁 User/                       # 👤 User-Only Features
│   │   │       ├── DashboardController.php    (View own submissions)
│   │   │       └── MemberController.php       (Register members)
│   │   │
│   │   └── 📁 Middleware/
│   │       ├── IsAdmin.php                    # Verify admin role
│   │       ├── IsSecretary.php                # Verify secretary role
│   │       └── IsUser.php                     # Verify user role
│   │
│   └── 📁 Models/
│       ├── User.php
│       ├── Member.php
│       └── Notification.php
│
├── 📁 resources/
│   └── 📁 views/
│       ├── 📁 admin/                          # 👨‍💼 Admin Views
│       │   └── dashboard.blade.php
│       │
│       ├── 📁 auth/                           # 🔐 Authentication Views
│       │   ├── login.blade.php
│       │   └── register.blade.php
│       │
│       ├── 📁 secretary/                      # 👩‍💻 Secretary Views
│       │   └── dashboard.blade.php
│       │
│       ├── 📁 user/                           # 👤 User Views
│       │   └── dashboard.blade.php
│       │
│       ├── 📁 members/                        # 📋 Shared Member Forms
│       │   └── create.blade.php
│       │
│       ├── 📁 layouts/                        # 🎨 Shared Layouts
│       │   ├── app.blade.php
│       │   └── dashboard-header.blade.php
│       │
│       ├── dashboard.blade.php                # Legacy unified dashboard
│       └── welcome.blade.php                  # Public welcome page
│
├── 📁 database/
│   ├── 📁 seeders/
│   │   ├── DatabaseSeeder.php                 # Main seeder (calls all)
│   │   ├── AdminSeeder.php                    # Seeds admin users
│   │   ├── SecretarySeeder.php                # Seeds secretary users
│   │   └── UserSeeder.php                     # Seeds regular users
│   │
│   └── 📁 migrations/
│       ├── 0001_01_01_000000_create_users_table.php
│       ├── 0001_01_01_000001_create_cache_table.php
│       ├── 0001_01_01_000002_create_jobs_table.php
│       ├── 2026_01_27_005741_add_role_to_users_table.php
│       ├── 2026_01_27_051034_create_members_table.php
│       ├── 2026_01_27_074938_add_fields_to_members_table.php
│       ├── 2026_01_27_075345_add_more_fields_to_members_table.php
│       ├── 2026_01_29_add_status_to_members_table.php
│       └── 2026_01_29_create_notifications_table.php
│
├── 📁 routes/
│   └── web.php                                # All routes (organized by role)
│
├── ROLE_BASED_STRUCTURE.md                   # Role organization documentation
├── composer.json                              # PHP dependencies
├── package.json                               # Node dependencies
└── vite.config.js                             # Frontend build configuration
```

## Role Assignment Flow

```
┌─────────────────────────────────────────────────────────────┐
│                    User Registration                         │
│              (Auth\RegisterController)                       │
│                   ↓ Role Assignment                          │
└─────────────────────────────────────────────────────────────┘
            │                  │                  │
            ↓                  ↓                  ↓
    ┌──────────────┐  ┌──────────────┐  ┌──────────────┐
    │   ADMIN      │  │  SECRETARY   │  │    USER      │
    │              │  │              │  │              │
    │ AdminSeeder  │  │SecretarySeeder│  │  UserSeeder  │
    │              │  │              │  │              │
    │ admin@       │  │ secretary1@  │  │ user@        │
    │ example.com  │  │ example.com  │  │ example.com  │
    └──────────────┘  │ secretary2@  │  └──────────────┘
                      │ example.com  │
                      └──────────────┘
            │                  │                  │
            ↓                  ↓                  ↓
    ┌──────────────┐  ┌──────────────┐  ┌──────────────┐
    │   /admin/*   │  │ /secretary/* │  │   /user/*    │
    │   Routes     │  │   Routes     │  │   Routes     │
    └──────────────┘  └──────────────┘  └──────────────┘
            │                  │                  │
            ↓                  ↓                  ↓
    ┌──────────────┐  ┌──────────────┐  ┌──────────────┐
    │ IsAdmin      │  │ IsSecretary  │  │   IsUser     │
    │ Middleware   │  │ Middleware   │  │ Middleware   │
    └──────────────┘  └──────────────┘  └──────────────┘
            │                  │                  │
            ↓                  ↓                  ↓
    ┌──────────────┐  ┌──────────────┐  ┌──────────────┐
    │  Admin       │  │ Secretary    │  │   User       │
    │  Controllers │  │ Controllers  │  │ Controllers  │
    └──────────────┘  └──────────────┘  └──────────────┘
            │                  │                  │
            ↓                  ↓                  ↓
    ┌──────────────┐  ┌──────────────┐  ┌──────────────┐
    │  Admin       │  │ Secretary    │  │   User       │
    │  Dashboard   │  │ Dashboard    │  │ Dashboard    │
    │  Views       │  │ Views        │  │ Views        │
    └──────────────┘  └──────────────┘  └──────────────┘
```

## Notification Flow Diagram

```
Member Registration Process:
┌──────────────────┐
│  User submits    │
│  member form     │
└─────────┬────────┘
          │
          ↓
┌─────────────────────────────────────────┐
│  UserMemberController::store()           │
│  - Creates Member record                │
│  - Queries ALL secretaries (by role)    │
└─────────┬───────────────────────────────┘
          │
          ↓
┌──────────────────────────────────────┐
│  For each secretary:                  │
│  Notification::create([               │
│    'user_id' => secretary->id,        │
│    'type' => 'member_registration',   │
│    'message' => 'New member...'       │
│  ])                                   │
└────┬─────────────────────────┬────────┘
     │                         │
     ↓                         ↓
  Secretary 1              Secretary 2
  Dashboard              Dashboard
  Notification           Notification


Verification Process:
┌──────────────────┐
│  Secretary 1     │
│  verifies member │
└─────────┬────────┘
          │
          ↓
┌─────────────────────────────────────────┐
│  SecretaryMemberController::verify()     │
│  - Updates Member status to 'approved'  │
│  - Queries Secretary 2 and Admin users  │
└─────────┬───────────────────────────────┘
          │
          ↓
┌──────────────────────────────────────┐
│  For Secretary 2 & Admin:             │
│  Notification::create([               │
│    'user_id' => user->id,             │
│    'type' => 'verification_complete',│
│    'message' => 'Member verified...' │
│  ])                                   │
└────┬─────────────────────────┬────────┘
     │                         │
     ↓                         ↓
  Secretary 2              Admin
  Dashboard              Dashboard
  Notification           Notification
```

## Security Boundaries

```
┌────────────────────────────────────────────────────────┐
│           Authentication Check (All Routes)            │
│         (Must be logged in to access /admin, etc)     │
└────────────────────────────────────────────────────────┘
                        │
         ┌──────────────┼──────────────┐
         ↓              ↓              ↓
    ┌─────────┐  ┌──────────┐  ┌──────────┐
    │ IsAdmin │  │IsSecretary│  │ IsUser   │
    │         │  │           │  │          │
    │ Only    │  │ Only      │  │ Only     │
    │ 'admin' │  │'secretary'│  │ 'user'   │
    │ role    │  │ role      │  │ role     │
    └────┬────┘  └─────┬─────┘  └────┬─────┘
         │              │             │
         ↓              ↓             ↓
    /admin/*      /secretary/*    /user/*
    routes        routes          routes
```

## File Organization Benefits

✅ **Separation of Concerns**: Each role has dedicated controllers, views, and seeders
✅ **Easy Maintenance**: Finding role-specific code is straightforward  
✅ **Clear Permissions**: Middleware enforces role-based access
✅ **Scalability**: Adding new roles follows the same pattern
✅ **Security**: Role-based queries prevent unauthorized access
✅ **Documentation**: Structure is self-documenting
