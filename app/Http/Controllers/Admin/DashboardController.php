<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Member;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        // Only admin can access
        if (Auth::user()->role !== 'admin') {
            return redirect()->route('dashboard')->with('error', 'Unauthorized access.');
        }

        $totalMembers = Member::count();
        $totalUsers = User::where('role', 'user')->count();
        $totalSecretaries = User::where('role', 'secretary')->count();
        $pendingSubmissions = Member::where('status', 'submitted')->count();
        $approvedMembers = Member::where('status', 'approved')->count();

        return view('admin.dashboard', [
            'totalMembers' => $totalMembers,
            'totalUsers' => $totalUsers,
            'totalSecretaries' => $totalSecretaries,
            'pendingSubmissions' => $pendingSubmissions,
            'approvedMembers' => $approvedMembers,
        ]);
    }
}
