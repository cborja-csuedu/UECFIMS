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
