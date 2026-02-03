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

    <div class="role-section">
        <h2>Secretary Two Dashboard</h2>
        <p>As Secretary Two you review verified member registrations. Members are approved by Secretary One before appearing here.</p>

        <div class="registrations-section">
            <h2>Approved Member Registrations</h2>
            <p>These registrations have been verified by Secretary One (view only).</p>

            @if($submissions->count() > 0)
                <table class="registrations-table">
                    <thead>
                        <tr>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Local Center</th>
                            <th>Submitted By</th>
                            <th>Approved Date</th>
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
                                <td>{{ $member->updated_at->format('M d, Y H:i') }}</td>
                                <td><span class="status-badge status-{{ $member->status }}">{{ ucfirst($member->status) }}</span></td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @else
                <div class="empty-message">No approved member registrations yet. They will appear here after Secretary One approves them.</div>
            @endif
        </div>
    </div>
@endsection
