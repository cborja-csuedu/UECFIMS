<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Member;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        // Only users can access
        if (Auth::user()->role !== 'user') {
            return redirect()->route('dashboard')->with('error', 'Unauthorized access.');
        }

        $submissions = Member::where('user_id', Auth::id())
            ->orderBy('created_at', 'desc')
            ->get();

        return view('user.dashboard', [
            'submissions' => $submissions,
        ]);
    }
}
