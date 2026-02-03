<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Member;
use App\Models\Notification;
use App\Models\User;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;

class MemberController extends Controller
{
    public function create()
    {
        // Only regular users (role 'user') can register members
        if (Auth::user()->role !== 'user') {
            return redirect()->route('dashboard')->with('error', 'Only regular users can register members.');
        }
        
        return view('members.create');
    }

    public function store(Request $request)
    {
        // Only regular users (role 'user') can register members
        if (Auth::user()->role !== 'user') {
            return redirect()->route('dashboard')->with('error', 'Only regular users can register members.');
        }

        $request->validate([
            'name' => 'required|string|max:255',
            'birthdate' => 'required|date',
            'local_center' => 'required|string|max:255',
            'address' => 'required|string',
            'zip_code' => 'required|string|max:10',
            'phone' => 'nullable|string|max:15',
            'email' => 'nullable|email|max:255',
            'gender' => 'nullable|in:male,female,other',
            'civil_status' => 'nullable|in:single,married,widowed,divorced',
            'occupation' => 'nullable|string|max:255',
            'emergency_contact_name' => 'nullable|string|max:255',
            'emergency_contact_phone' => 'nullable|string|max:15',
            'baptism_date' => 'nullable|date',
            'membership_date' => 'nullable|date',
            'picture' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'esign' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        $data = $request->only(['name', 'birthdate', 'local_center', 'address', 'zip_code', 'phone', 'email', 'gender', 'civil_status', 'occupation', 'emergency_contact_name', 'emergency_contact_phone', 'baptism_date', 'membership_date']);
        $data['user_id'] = auth()->id();
        $data['status'] = 'submitted'; // Explicitly set status for new members

        if ($request->hasFile('picture')) {
            $data['picture'] = $request->file('picture')->store('members/pictures', 'public');
        }

        if ($request->hasFile('esign')) {
            $data['esign'] = $request->file('esign')->store('members/esigns', 'public');
        }

        $member = Member::create($data);

        // Send notification to all secretaries
        $secretaries = User::where('role', 'secretary')->get();
        foreach ($secretaries as $secretary) {
            Notification::create([
                'user_id' => $secretary->id,
                'member_id' => $member->id,
                'type' => 'member_registration',
                'title' => 'New Member Registration',
                'message' => "A new member registration has been submitted by " . auth()->user()->name . ". Member name: " . $member->name . ". Please review and verify the information.",
                'read' => false,
            ]);
        }

        return redirect()->route('dashboard')->with('success', 'Member registered successfully.');
    }

    public function verify(Member $member)
    {
        // Only users with the verify permission can verify
        if (!auth()->user()->hasPermission('verify_member')) {
            return redirect()->route('dashboard')->with('error', 'Unauthorized action.');
        }

        $member->update(['status' => 'approved']);

        // Notify Secretary 2 and Admin about verification
        $secretary2 = User::where('email', 'secretary2@example.com')->first();
        $admin = User::where('email', 'admin@example.com')->first();

        /** @var Member $member */
        if ($secretary2) {
            Notification::create([
                'user_id' => $secretary2->id,
                'member_id' => $member->id,
                'type' => 'verification_complete',
                'title' => 'Member Registration Verified',
                'message' => "Member registration for " . $member->name . " has been verified by a secretary. Status is now approved.",
                'read' => false,
            ]);
        }

        if ($admin) {
            Notification::create([
                'user_id' => $admin->id,
                'member_id' => $member->id,
                'type' => 'verification_complete',
                'title' => 'Member Registration Verified',
                'message' => "Member registration for " . $member->name . " has been verified by a secretary. Status is now approved.",
                'read' => false,
            ]);
        }

        return redirect()->route('dashboard')->with('success', 'Member registration verified successfully. Secretary 2 and Admin have been notified.');
    }
}

