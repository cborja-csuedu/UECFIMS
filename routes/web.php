<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\User\MemberController as UserMemberController;
use App\Http\Controllers\User\DashboardController as UserDashboardController;
use App\Http\Controllers\Secretary\MemberController as SecretaryMemberController;
use App\Http\Controllers\Secretary\SecretaryDashboardController as SecretaryDashboardController;
use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;

Route::get('/', function () {
    return view('welcome');
});

// Debug route - remove after testing
Route::get('/debug/notifications', function () {
    $notifications = \App\Models\Notification::with('user', 'member')->latest()->take(10)->get();
    $members = \App\Models\Member::with('user')->latest()->take(5)->get();
    $secretaries = \App\Models\User::where('role', 'secretary')->get();
    
    return response()->json([
        'recent_notifications' => $notifications,
        'recent_members' => $members,
        'secretaries' => $secretaries,
        'total_notifications' => \App\Models\Notification::count(),
    ]);
});

Route::get('/debug/secretary-data/{userId}', function ($userId) {
    try {
        $notifications = \App\Models\Notification::where('user_id', $userId)
            ->orderBy('created_at', 'desc')
            ->limit(10)
            ->get();
        
        return response()->json([
            'status' => 'ok',
            'user_id' => $userId,
            'notification_count' => $notifications->count(),
            'notifications' => $notifications,
        ]);
    } catch (\Exception $e) {
        return response()->json(['error' => $e->getMessage()], 500);
    }
});

Route::get('/debug/test-member', function () {
    try {
        $user = \App\Models\User::where('email','user@example.com')->first();
        if (!$user) {
            return response()->json(['error' => 'User not found'], 404);
        }
        
        $member = \App\Models\Member::create([
            'name' => 'Test Member ' . time(),
            'birthdate' => '1990-01-01',
            'local_center' => 'Test Center',
            'address' => 'Test Address',
            'zip_code' => '12345',
            'user_id' => $user->id,
            'status' => 'submitted'
        ]);
        
        // Manually create notification like the controller does
        $secretaries = \App\Models\User::where('role', 'secretary')->get();
        foreach ($secretaries as $secretary) {
            \App\Models\Notification::create([
                'user_id' => $secretary->id,
                'member_id' => $member->id,
                'type' => 'member_registration',
                'title' => 'New Member Registration',
                'message' => "A new member registration has been submitted. Member name: " . $member->name . ". Please review and verify the information.",
                'read' => false,
            ]);
        }
        
        return response()->json([
            'success' => true,
            'member' => $member,
            'notifications_created' => count($secretaries),
            'secretaries' => $secretaries
        ]);
    } catch (\Exception $e) {
        return response()->json(['error' => $e->getMessage()], 500);
    }
});

Route::get('/debug/reset-test-data', function () {
    try {
        // Delete all members and their notifications
        $members = \App\Models\Member::all();
        foreach ($members as $member) {
            \App\Models\Notification::where('member_id', $member->id)->delete();
            $member->delete();
        }
        
        return response()->json([
            'status' => 'reset',
            'message' => 'Test data cleared.',
            'members_remaining' => \App\Models\Member::count(),
            'notifications_remaining' => \App\Models\Notification::count(),
        ]);
    } catch (\Exception $e) {
        return response()->json(['error' => $e->getMessage()], 500);
    }
});

Route::get('/debug/workflow-test', function () {
    try {
        // Create a new member with 'submitted' status
        $user = \App\Models\User::where('email', 'user@example.com')->first();
        
        $member = \App\Models\Member::create([
            'name' => 'Workflow Test Member ' . time(),
            'birthdate' => '1990-01-15',
            'local_center' => 'Test Center',
            'address' => 'Test Address 123',
            'zip_code' => '12345',
            'user_id' => $user->id,
            'status' => 'submitted',
        ]);
        
        // Create notifications for both secretaries
        $secretaries = \App\Models\User::where('role', 'secretary')->get();
        foreach ($secretaries as $secretary) {
            \App\Models\Notification::create([
                'user_id' => $secretary->id,
                'member_id' => $member->id,
                'type' => 'member_registration',
                'title' => 'New Member Registration',
                'message' => "New member: " . $member->name,
                'read' => false,
            ]);
        }
        
        return response()->json([
            'status' => 'created',
            'member' => [
                'id' => $member->id,
                'name' => $member->name,
                'status' => $member->status,
            ],
            'notifications_created' => count($secretaries),
            'sec1_submitted_members' => \App\Models\Member::where('status', 'submitted')->count(),
            'sec2_approved_members' => \App\Models\Member::where('status', 'approved')->count(),
        ]);
    } catch (\Exception $e) {
        return response()->json(['error' => $e->getMessage()], 500);
    }
});

Route::get('/debug/check-db', function () {
    try {
        \Illuminate\Support\Facades\DB::connection()->getPdo();
        $tables = \Illuminate\Support\Facades\Schema::getTables();
        return response()->json([
            'status' => 'connected',
            'tables' => $tables
        ]);
    } catch (\Exception $e) {
        return response()->json(['error' => $e->getMessage()], 500);
    }
});

Route::get('/debug/secretary1-view', function () {
    try {
        // Exactly what Secretary 1 dashboard queries
        $submissions = \App\Models\Member::where('status', 'submitted')->orderBy('created_at', 'desc')->get();
        
        // Also get notifications
        $notifications = \App\Models\Notification::where('user_id', 1)
            ->orderBy('created_at', 'desc')
            ->limit(10)
            ->get();
        
        return response()->json([
            'secretary_1_view' => [
                'submitted_members_count' => $submissions->count(),
                'submitted_members' => $submissions->map(function($m) {
                    return [
                        'id' => $m->id,
                        'name' => $m->name,
                        'status' => $m->status,
                        'created_at' => $m->created_at,
                        'submitted_by' => $m->user->name,
                    ];
                }),
                'notifications_count' => $notifications->count(),
                'notifications' => $notifications->map(function($n) {
                    return [
                        'id' => $n->id,
                        'title' => $n->title,
                        'message' => $n->message,
                        'read' => $n->read,
                    ];
                }),
            ],
        ]);
    } catch (\Exception $e) {
        return response()->json(['error' => $e->getMessage()], 500);
    }
});

Route::get('/debug/testing-guide', function () {
    $sec1_members = \App\Models\Member::where('status', 'submitted')->count();
    $sec2_members = \App\Models\Member::where('status', 'approved')->count();
    
    return response()->json([
        'status' => 'ready',
        'message' => 'System is working correctly. Follow the steps below:',
        'test_data' => [
            'submitted_members_for_sec1' => $sec1_members,
            'approved_members_for_sec2' => $sec2_members,
        ],
        'steps' => [
            '1. Clear browser cache (Ctrl+Shift+Delete)',
            '2. Go to http://localhost:8000',
            '3. Click "Login"',
            '4. Use credentials:',
            '   Email: secretary1@example.com',
            '   Password: password',
            '5. After login, click "Dashboard"',
            '6. You should be redirected to /secretary/dashboard1',
            '7. You should see ' . $sec1_members . ' submitted member(s) in the table',
            '8. You should see notifications at the top',
        ],
        'debug_endpoints' => [
            '/debug/notifications - Shows all notifications in database',
            '/debug/secretary1-view - Shows exactly what Secretary 1 sees',
            '/debug/workflow-test - Create a test member',
            '/debug/reset-test-data - Clear all test data',
        ],
    ]);
});

// Authentication Routes
Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginController::class, 'login']);
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

Route::get('/register', [RegisterController::class, 'showRegistrationForm'])->name('register');
Route::post('/register', [RegisterController::class, 'register']);

// Protected Routes
Route::middleware('auth')->group(function () {
    Route::get('/dashboard', function () {
        /** @var \App\Models\User $user */
        $user = auth()->user();
        $role = $user?->role ?? 'user';
        
        if ($role === 'admin') {
            return redirect()->route('admin.dashboard');
        } elseif ($role === 'secretary') {
            return redirect()->route('secretary.dashboard');
        } else {
            return redirect()->route('user.dashboard');
        }
    })->name('dashboard');

    // Admin Routes
    Route::prefix('admin')->name('admin.')->middleware('admin')->group(function () {
        Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');
    });

    // Secretary Routes
    Route::prefix('secretary')->name('secretary.')->middleware('secretary')->group(function () {
        // main dashboard decides which secretary-specific dashboard to show
        Route::get('/dashboard', [SecretaryDashboardController::class, 'index'])->name('dashboard');

        // Secretary 1: has verification permission
        Route::get('/dashboard1', [SecretaryDashboardController::class, 'dashboard1'])->name('dashboard1')->middleware('permission:verify_member');

        // Secretary 2: view-only
        Route::get('/dashboard2', [SecretaryDashboardController::class, 'dashboard2'])->name('dashboard2');

        Route::get('/members/{member}', [SecretaryMemberController::class, 'show'])->name('members.show');
        Route::post('/members/{member}/verify', [SecretaryMemberController::class, 'verify'])->name('members.verify')->middleware('permission:verify_member');
    });

    // User Routes
    Route::prefix('user')->name('user.')->middleware('user')->group(function () {
        Route::get('/dashboard', [UserDashboardController::class, 'index'])->name('dashboard');
        Route::get('/members/create', [UserMemberController::class, 'create'])->name('members.create');
        Route::post('/members', [UserMemberController::class, 'store'])->name('members.store');
    });

    // General Member Routes (accessible by users)
    Route::get('/members/create', [UserMemberController::class, 'create'])->name('members.create');
    Route::post('/members', [UserMemberController::class, 'store'])->name('members.store');
    Route::post('/members/{member}/verify', [SecretaryMemberController::class, 'verify'])->name('members.verify');
});
