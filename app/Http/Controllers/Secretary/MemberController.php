<?php

namespace App\Http\Controllers\Secretary;

use App\Http\Controllers\Controller;
use App\Models\Member;
use App\Models\Notification;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class MemberController extends Controller
{
    public function show(Member $member)
    {
        // Only secretaries can view member details
        if (auth()->user()->role !== 'secretary') {
            return redirect()->route('dashboard')->with('error', 'Unauthorized action.');
        }

        return view('secretary.member-details', [
            'member' => $member,
        ]);
    }

    public function verify(Member $member)
    {
        // Only Secretary 1 can verify initially
        if (auth()->user()->email !== 'secretary1@example.com') {
            return redirect()->route('dashboard')->with('error', 'Unauthorized action.');
        }

        $member->update(['status' => 'approved']);

        // Notify Secretary 2 and Admin about verification
        $secretary2 = User::where('email', 'secretary2@example.com')->first();
        $admin = User::where('role', 'admin')->first();

        /** @var Member $member */
        if ($secretary2) {
            Notification::create([
                'user_id' => $secretary2->id,
                'member_id' => $member->id,
                'type' => 'verification_complete',
                'title' => 'Member Registration Verified',
                'message' => "Member registration for " . $member->name . " has been verified by Secretary One. Status is now approved.",
                'read' => false,
            ]);
        }

        if ($admin) {
            Notification::create([
                'user_id' => $admin->id,
                'member_id' => $member->id,
                'type' => 'verification_complete',
                'title' => 'Member Registration Verified',
                'message' => "Member registration for " . $member->name . " has been verified by Secretary One. Status is now approved.",
                'read' => false,
            ]);
        }

        return redirect()->route('dashboard')->with('success', 'Member registration verified successfully. Secretary 2 and Admin have been notified.');
    }
}
