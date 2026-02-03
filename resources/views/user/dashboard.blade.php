@extends('layouts.dashboard-header')

@section('content')
    <!-- Notifications Section -->
    @php
        $notifications = \App\Models\Notification::where('user_id', Auth::id())
            ->orderBy('created_at', 'desc')
            ->limit(10)
            ->get();
    @endphp

    @if($notifications->count() > 0)
        <div class="notifications-section">
            <h3>📬 Notifications</h3>
            @foreach($notifications as $notification)
                <div class="notification-item @if(!$notification->read) unread @endif">
                    <div class="notification-title">{{ $notification->title }}</div>
                    <div class="notification-message">{{ $notification->message }}</div>
                    <div class="notification-time">{{ $notification->created_at->format('M d, Y H:i') }}</div>
                </div>
            @endforeach
        </div>
    @endif

    <!-- Regular User Dashboard -->
    <div class="role-section">
        <h2>Student Dashboard</h2>
        <p>Welcome to UECFIMS. Access your personal information and academic resources.</p>

        <div class="dashboard-grid">
            <div class="card">
                <h3>My Profile</h3>
                <p>View and update your personal information</p>
                <button class="btn">View Profile</button>
            </div>
            <div class="card">
                <h3>Academic Records</h3>
                <p>Check your grades and academic history</p>
                <button class="btn">View Records</button>
            </div>
            <div class="card">
                <h3>Class Schedule</h3>
                <p>View your current class schedule</p>
                <button class="btn">My Schedule</button>
            </div>
            <div class="card">
                <h3>Register New Member</h3>
                <p>Register a new member with required information</p>
                <a href="{{ route('members.create') }}" class="btn">Register Member</a>
            </div>
            <div class="card">
                <h3>Support</h3>
                <p>Contact support for assistance</p>
                <button class="btn">Get Help</button>
            </div>
        </div>
    </div>

    <!-- Submitted Registrations Section -->
    <div class="registrations-section">
        <h2>My Submitted Registrations</h2>
        <p>View the status of your member registration submissions.</p>
        
        @php
            $myRegistrations = \App\Models\Member::where('user_id', Auth::id())->orderBy('created_at', 'desc')->get();
        @endphp

        @if($myRegistrations->count() > 0)
            <table class="registrations-table">
                <thead>
                    <tr>
                        <th>Member Name</th>
                        <th>Local Center</th>
                        <th>Submitted Date</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($myRegistrations as $member)
                        <tr>
                            <td>{{ $member->name }}</td>
                            <td>{{ $member->local_center }}</td>
                            <td>{{ $member->created_at->format('M d, Y H:i') }}</td>
                            <td><span class="status-badge status-{{ $member->status }}">{{ ucfirst($member->status) }}</span></td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @else
            <div class="empty-message">You haven't submitted any member registrations yet. Click "Register Member" to submit one.</div>
        @endif
    </div>
@endsection
