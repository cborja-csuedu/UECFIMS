<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ config('app.name', 'Laravel') }} - Dashboard</title>
    <!-- Styles -->
    <style>
        body { font-family: Arial, sans-serif; background: #f4f4f4; margin: 0; padding: 20px; }
        .header { display: flex; justify-content: space-between; align-items: center; background: white; padding: 1rem; border-radius: 8px; margin-bottom: 20px; box-shadow: 0 0 10px rgba(0,0,0,0.1); }
        .dashboard-content { background: white; padding: 2rem; border-radius: 8px; box-shadow: 0 0 10px rgba(0,0,0,0.1); }
        .user-info { margin-bottom: 20px; border-bottom: 1px solid #eee; padding-bottom: 20px; }
        .role-section { margin-top: 20px; }
        .dashboard-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 20px; margin-top: 20px; }
        .card { background: #f8f9fa; border: 1px solid #dee2e6; border-radius: 8px; padding: 20px; text-align: center; transition: transform 0.2s; }
        .card:hover { transform: translateY(-5px); box-shadow: 0 4px 8px rgba(0,0,0,0.1); }
        .card h3 { margin-top: 0; color: #333; }
        .card p { color: #666; margin-bottom: 15px; }
        .btn { background: #007bff; color: white; border: none; padding: 10px 20px; border-radius: 5px; cursor: pointer; transition: background 0.3s; }
        .btn:hover { background: #0056b3; }
        .registrations-section { margin-top: 30px; border-top: 2px solid #007bff; padding-top: 20px; }
        .registrations-table { width: 100%; border-collapse: collapse; margin-top: 15px; }
        .registrations-table th, .registrations-table td { padding: 12px; text-align: left; border-bottom: 1px solid #dee2e6; }
        .registrations-table th { background: #007bff; color: white; }
        .registrations-table tr:hover { background: #f5f5f5; }
        .status-badge { padding: 5px 10px; border-radius: 4px; font-weight: bold; }
        .status-submitted { background: #ffc107; color: #333; }
        .status-approved { background: #28a745; color: white; }
        .status-rejected { background: #dc3545; color: white; }
        .empty-message { text-align: center; padding: 20px; color: #666; font-style: italic; }
        .notifications-section { margin-top: 30px; border: 2px solid #17a2b8; border-radius: 8px; padding: 20px; background: #e7f3f8; }
        .notifications-section h3 { margin-top: 0; color: #17a2b8; }
        .notification-item { background: white; border-left: 4px solid #17a2b8; padding: 15px; margin-bottom: 10px; border-radius: 4px; }
        .notification-item.unread { background: #fff9e6; border-left-color: #ffc107; }
        .notification-title { font-weight: bold; color: #333; margin-bottom: 5px; }
        .notification-message { color: #666; font-size: 0.95em; margin-bottom: 10px; }
        .notification-time { font-size: 0.85em; color: #999; }
        .btn-small { background: #28a745; color: white; border: none; padding: 8px 15px; border-radius: 4px; cursor: pointer; transition: background 0.3s; font-size: 0.9em; }
        .btn-small:hover { background: #218838; }
        .btn-danger { background: #dc3545; }
        .btn-danger:hover { background: #c82333; }
    </style>
</head>
<body>
    <div class="header">
        <h1>UECFIMS Dashboard</h1>
        <div>
            <span>Welcome, {{ Auth::user()->name }} ({{ Auth::user()->role }})</span>
            <button onclick="history.back()" style="margin-left: 10px; padding: 5px 10px; background: #6c757d; color: white; border: none; border-radius: 4px; cursor: pointer;">Back</button>
            <form method="POST" action="{{ route('logout') }}" style="display: inline;">
                @csrf
                <button type="submit" style="margin-left: 10px; padding: 5px 10px; background: #dc3545; color: white; border: none; border-radius: 4px; cursor: pointer;">Logout</button>
            </form>
        </div>
    </div>

    <div class="dashboard-content">
        <div class="user-info">
            <h2>User Information</h2>
            <p><strong>Name:</strong> {{ Auth::user()->name }}</p>
            <p><strong>Email:</strong> {{ Auth::user()->email }}</p>
            <p><strong>Role:</strong> {{ Auth::user()->role }}</p>
        </div>

        @if(Auth::user()->role === 'admin')
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
                                    <th>Action</th>
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
                                    @if(Auth::user()->email === 'secretary1@example.com' && $member->status === 'submitted')
                                    <td>
                                        <form action="{{ route('members.verify', $member->id) }}" method="POST" style="display: inline;">
                                            @csrf
                                            <button type="submit" class="btn-small">✓ Verify</button>
                                        </form>
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

        @else
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
        @endif
    </div>
</body>
</html>