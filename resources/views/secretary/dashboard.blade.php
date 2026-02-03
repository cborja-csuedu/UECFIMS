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

    <!-- Secretary Dashboard -->
    <div class="role-section">
        <h2>Secretary Dashboard</h2>
        <p>As a secretary, you can manage student records, schedules, and administrative tasks.</p>

        <div class="dashboard-grid">
            <div class="card">
                <h3>Student Records</h3>
                <p>View and manage student information</p>
                <button class="btn">Student Records</button>
            </div>
            <div class="card">
                <h3>Schedule Management</h3>
                <p>Manage class schedules and appointments</p>
                <button class="btn">Manage Schedules</button>
            </div>
            <div class="card">
                <h3>Document Processing</h3>
                <p>Handle document submissions and approvals</p>
                <button class="btn">Process Documents</button>
            </div>
            <div class="card">
                <h3>Communication</h3>
                <p>Send notifications and announcements</p>
                <button class="btn">Send Messages</button>
            </div>
        </div>
    </div>

    <!-- Submitted Registrations Section -->
    <div class="registrations-section">
        <h2>Submitted Member Registrations</h2>
        <p>Review and manage member registration submissions. @if(Auth::user()->email === 'secretary1@example.com')Click "Verify" to approve and notify Secretary 2 and Admin.@endif</p>
        
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
                        @if(Auth::user()->email === 'secretary1@example.com')
                        <th>Actions</th>
                        @endif
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
                            @if(Auth::user()->email === 'secretary1@example.com')
                            <td>
                                <a href="{{ route('secretary.members.show', $member->id) }}" class="btn-small" title="View Details">👁️ View</a>
                                @if($member->status === 'submitted')
                                <form action="{{ route('secretary.members.verify', $member->id) }}" method="POST" style="display: inline;">
                                    @csrf
                                    <button type="submit" class="btn-small" title="Approve">✓ Approve</button>
                                </form>
                                @endif
                            </td>
                            @endif
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @else
            <div class="empty-message">No member registrations submitted yet.</div>
        @endif
    </div>
@endsection
