<?php

namespace App\Http\Controllers\Secretary;

use App\Http\Controllers\Controller;
use App\Models\Member;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        // Only secretaries can access
        if (Auth::user()->role !== 'secretary') {
            return redirect()->route('dashboard')->with('error', 'Unauthorized access.');
        }

        // Redirect to secretary-specific dashboard based on permissions
        if (Auth::user()->hasPermission('verify_member')) {
            return redirect()->route('secretary.dashboard1');
        }

        return redirect()->route('secretary.dashboard2');
    }
}
