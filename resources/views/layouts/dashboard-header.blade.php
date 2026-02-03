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
        .btn-small { background: #28a745; color: white; border: none; padding: 8px 15px; border-radius: 4px; cursor: pointer; transition: background 0.3s; font-size: 0.9em; text-decoration: none; display: inline-block; margin-right: 5px; }
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

        @yield('content')
    </div>
</body>
</html>
