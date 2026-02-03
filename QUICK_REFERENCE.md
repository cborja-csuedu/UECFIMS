# UECFIMS - Quick Reference Guide

## 🚀 Quick Start

### Fresh Setup
```bash
cd c:\xampp\htdocs\UECFIMS
php artisan migrate:fresh --seed
php artisan serve
```

### Login Credentials (After Seeding)
| Role | Email | Password |
|------|-------|----------|
| Admin | admin@example.com | password |
| Secretary 1 | secretary1@example.com | password |
| Secretary 2 | secretary2@example.com | password |
| User | user@example.com | password |

---

## 📂 File Locations by Role

### Admin Resources
- **Controller**: `app/Http/Controllers/Admin/DashboardController.php`
- **View**: `resources/views/admin/dashboard.blade.php`
- **Route**: `/admin/dashboard` (method: GET)
- **Middleware**: `admin`
- **Seeder**: `database/seeders/AdminSeeder.php`

### Secretary Resources
- **Controllers**: 
  - `app/Http/Controllers/Secretary/DashboardController.php`
  - `app/Http/Controllers/Secretary/MemberController.php`
- **View**: `resources/views/secretary/dashboard.blade.php`
- **Routes**: 
  - `/secretary/dashboard` (GET)
  - `/secretary/members/{id}/verify` (POST)
- **Middleware**: `secretary`
- **Seeder**: `database/seeders/SecretarySeeder.php`

### User Resources
- **Controllers**:
  - `app/Http/Controllers/User/DashboardController.php`
  - `app/Http/Controllers/User/MemberController.php`
- **View**: `resources/views/user/dashboard.blade.php`
- **Routes**:
  - `/user/dashboard` (GET)
  - `/user/members/create` (GET)
  - `/user/members` (POST)
- **Middleware**: `user`
- **Seeder**: `database/seeders/UserSeeder.php`

### Shared Resources
- **Auth Controllers**: `app/Http/Controllers/Auth/` (LoginController, RegisterController)
- **Auth Views**: `resources/views/auth/` (login, register)
- **Member Form**: `resources/views/members/create.blade.php`
- **Layouts**: `resources/views/layouts/` (app, dashboard-header)

---

## 🔄 Common Tasks

### Access Admin Dashboard
```
URL: http://localhost:8000/admin/dashboard
Login: admin@example.com / password
```

### Access Secretary Dashboard
```
URL: http://localhost:8000/secretary/dashboard
Login: secretary1@example.com or secretary2@example.com / password
```

### Access User Dashboard
```
URL: http://localhost:8000/user/dashboard
Login: user@example.com / password
```

### Register New Member (as User)
```
1. Login as user@example.com
2. Go to /user/dashboard
3. Click "Register Member"
4. Fill form at /user/members/create
5. Submit
6. Notifications sent to ALL secretaries
```

### Verify Member (as Secretary 1)
```
1. Login as secretary1@example.com
2. Go to /secretary/dashboard
3. Find member in registrations table
4. Click "Verify" button
5. Member status updated to 'approved'
6. Notifications sent to Secretary 2 and Admin
```

---

## 🔐 Route Protection

All protected routes require authentication. Routes are grouped by role:

### Admin Routes (Prefix: `/admin`)
- Must have role = 'admin'
- Uses `IsAdmin` middleware
- Accessible at: `/admin/*`

### Secretary Routes (Prefix: `/secretary`)
- Must have role = 'secretary'
- Uses `IsSecretary` middleware
- Accessible at: `/secretary/*`

### User Routes (Prefix: `/user`)
- Must have role = 'user'
- Uses `IsUser` middleware
- Accessible at: `/user/*`

### Authentication Routes (Prefix: None)
- Open to all (public)
- `/login`, `/register`, `/logout`

---

## 📋 Notification Types

### Member Registration Notification
- **Sent To**: All users with role = 'secretary'
- **Triggered By**: User submits member registration
- **Type**: 'member_registration'
- **Title**: 'New Member Registration'

### Verification Complete Notification
- **Sent To**: All users with role = 'secretary' (except S1) + admin
- **Triggered By**: Secretary 1 verifies member
- **Type**: 'verification_complete'
- **Title**: 'Member Registration Verified'

---

## 🛠️ Middleware Details

### IsAdmin Middleware
```php
// Checks: auth()->user()->role === 'admin'
// Redirects: 'dashboard' with error if failed
```

### IsSecretary Middleware
```php
// Checks: auth()->user()->role === 'secretary'
// Redirects: 'dashboard' with error if failed
```

### IsUser Middleware
```php
// Checks: auth()->user()->role === 'user'
// Redirects: 'dashboard' with error if failed
```

---

## 📊 Database Models

### User Model
```php
$user->id           // User ID
$user->name         // User name
$user->email        // User email
$user->role         // Role: 'admin', 'secretary', 'user'
$user->password     // Hashed password
```

### Member Model
```php
$member->id              // Member ID
$member->name            // Member name
$member->user_id         // User who submitted
$member->status          // Status: 'submitted', 'approved', 'rejected'
$member->created_at      // Submission date
$member->local_center    // Local center
$member->phone           // Phone number
$member->email           // Member email
// ... more fields
```

### Notification Model
```php
$notification->id         // Notification ID
$notification->user_id    // User receiving notification
$notification->member_id  // Related member (if any)
$notification->type       // Type of notification
$notification->title      // Notification title
$notification->message    // Notification message
$notification->read       // Read status (boolean)
```

---

## 🔍 Testing Checklist

- [ ] Can admin access `/admin/dashboard`
- [ ] Admin cannot access `/secretary/dashboard`
- [ ] Secretary can access `/secretary/dashboard`
- [ ] Secretary cannot access `/admin/dashboard`
- [ ] User can access `/user/dashboard`
- [ ] User cannot access `/secretary/dashboard`
- [ ] User can register member
- [ ] Notifications sent to ALL secretaries (not just S1)
- [ ] Secretary 1 can verify member
- [ ] Secretary 2 and Admin notified when S1 verifies
- [ ] Unregistered user cannot access `/admin/*`
- [ ] Logout works correctly

---

## 📚 Documentation Files

| File | Purpose |
|------|---------|
| `IMPLEMENTATION_SUMMARY.md` | Complete implementation details |
| `ROLE_BASED_STRUCTURE.md` | Detailed role organization guide |
| `DIRECTORY_STRUCTURE.md` | Visual directory tree and diagrams |
| `README.md` | Original project README |

---

## 🆘 Troubleshooting

### "Unauthorized access" message
- Check your login role matches the route
- Verify user role in database: `SELECT * FROM users WHERE email = '...'`
- Clear sessions: `php artisan tinker` → `Session::flush()`

### Notifications not appearing
- Check notifications table: `SELECT * FROM notifications WHERE user_id = ...`
- Verify user role in dashboard view
- Check browser cache and refresh

### Route not found
- Verify URL matches pattern (e.g., `/admin/*` not just `/admin`)
- Check middleware aliases in `bootstrap/app.php`
- Run `php artisan route:list` to see all routes

### User keeps redirecting to dashboard
- Check middleware is correctly blocking the route
- Verify user role in database
- Check if `auth()->check()` is returning true

---

## 💡 Tips & Tricks

1. **View All Routes**
   ```bash
   php artisan route:list
   ```

2. **Check Current User**
   ```bash
   php artisan tinker
   > auth()->user()
   ```

3. **Query Users by Role**
   ```bash
   php artisan tinker
   > User::where('role', 'secretary')->get()
   ```

4. **Clear All Data and Reseed**
   ```bash
   php artisan migrate:fresh --seed
   ```

5. **Create Custom User**
   ```bash
   php artisan tinker
   > User::create(['name' => 'Test', 'email' => 'test@test.com', 'password' => Hash::make('password'), 'role' => 'admin'])
   ```

---

## 📞 Key Features

✅ **Role-Based Access Control** - Each role has dedicated routes
✅ **Notification System** - All secretaries receive notifications
✅ **Member Registration** - Users can submit member information
✅ **Verification Workflow** - Secretary 1 verifies, notifies others
✅ **Admin Dashboard** - Overview of all system activity
✅ **Security** - Email-based lookups replaced with role queries
✅ **Scalability** - Easy to add new roles or features

---

**Last Updated**: January 29, 2026  
**Version**: 1.0 (Role-Based Organization)
