<!DOCTYPE html>
<html>
<head>
    <title>Users List</title>
    <style>
        body { font-family: Arial, sans-serif; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { border: 1px solid #ddd; padding: 8px; text-align: left; }
        th { background-color: #f2f2f2; }
        h1 { text-align: center; color: #333; }
        .filter-info { margin-bottom: 20px; color: #666; font-style: italic; }
    </style>
</head>
<body>
    <h1>Users List</h1>
    
    @if($supplier)
        <div class="filter-info">
            Filtered by Supplier: {{ $supplier->name }}
        </div>
    @endif

    <table>
        <thead>
            <tr>
                <th>Name</th>
                <th>Email</th>
                <th>Role</th>
                <th>Supplier</th>
            </tr>
        </thead>
        <tbody>
            @foreach($users as $user)
            <tr>
                <td>{{ $user->name }}</td>
                <td>{{ $user->email }}</td>
                <td>{{ $user->role->name }}</td>
                <td>{{ $user->supplier ? $user->supplier->name : '-' }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html> 