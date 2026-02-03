<?php

namespace App\Http\Controllers\Secretary;

use App\Http\Controllers\Controller;
use App\Models\Member;
use Illuminate\Support\Facades\Auth;

class SecretaryDashboardController extends Controller
{
    public function index()
    {
        // Only secretaries can access
        if (Auth::user()->role !== 'secretary') {
            return redirect()->route('dashboard')->with('error', 'Unauthorized access.');
        }

        // If the user has the verify permission, send to secretary1 dashboard
        if (Auth::user()->hasPermission('verify_member')) {
            return redirect()->route('secretary.dashboard1');
        }

        // Otherwise show secretary2 dashboard
        return redirect()->route('secretary.dashboard2');
    }

    public function dashboard1()
    {
        if (Auth::user()->role !== 'secretary' || !Auth::user()->hasPermission('verify_member')) {
            return redirect()->route('dashboard')->with('error', 'Unauthorized access.');
        }

        $submissions = Member::orderBy('created_at', 'desc')->get();

        return view('secretary.dashboard1', compact('submissions'));
    }

    public function dashboard2()
    {
        if (Auth::user()->role !== 'secretary') {
            return redirect()->route('dashboard')->with('error', 'Unauthorized access.');
        }

        $submissions = Member::orderBy('created_at', 'desc')->get();

        return view('secretary.dashboard2', compact('submissions'));
    }
}
