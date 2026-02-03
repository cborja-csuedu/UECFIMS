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

    <!-- Admin Dashboard -->
    <div class="role-section">
        <h2>Administrator Dashboard</h2>
        <p>As an administrator, you have full access to manage the UECFIMS system.</p>

        <div class="dashboard-grid">
            <div class="card">
                <h3>User Management</h3>
                <p>Manage all users, roles, and permissions</p>
                <button class="btn">Manage Users</button>
            </div>
            <div class="card">
                <h3>System Settings</h3>
                <p>Configure system preferences and settings</p>
                <button class="btn">System Config</button>
            </div>
            <div class="card">
                <h3>Reports & Analytics</h3>
                <p>View system reports and analytics</p>
                <button class="btn">View Reports</button>
            </div>
            <div class="card">
                <h3>Database Management</h3>
                <p>Backup, restore, and maintain database</p>
                <button class="btn">Database Tools</button>
            </div>
        </div>
    </div>

    <!-- Submitted Registrations Section for Admin -->
    <div class="registrations-section">
        <h2>All Member Registrations</h2>
        <p>View all member registration submissions across the system.</p>
        
        @php
            $submissions = \App\Models\Member::orderBy('created_at', 'desc')->get();
        @endphp

        @if($submissions->count() > 0)
            <table class="registrations-table">
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Local Center</th>
                        <th>Submitted By</th>
                        <th>Submitted Date</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($submissions as $member)
                        <tr>
                            <td>{{ $member->name }}</td>
                            <td>{{ $member->email ?? 'N/A' }}</td>
                            <td>{{ $member->local_center }}</td>
                            <td>{{ $member->user->name }}</td>
                            <td>{{ $member->created_at->format('M d, Y H:i') }}</td>
                            <td><span class="status-badge status-{{ $member->status }}">{{ ucfirst($member->status) }}</span></td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @else
            <div class="empty-message">No member registrations submitted yet.</div>
        @endif
    </div>
@endsection
