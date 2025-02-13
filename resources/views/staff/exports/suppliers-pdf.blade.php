<!DOCTYPE html>
<html>
<head>
    <title>Suppliers List</title>
    <style>
        body { font-family: Arial, sans-serif; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { border: 1px solid #ddd; padding: 8px; text-align: left; }
        th { background-color: #f2f2f2; }
        h1 { text-align: center; color: #333; }
    </style>
</head>
<body>
    <h1>Suppliers List</h1>

    <table>
        <thead>
            <tr>
                <th>Supplier Name</th>
                <th>Total Users</th>
            </tr>
        </thead>
        <tbody>
            @foreach($suppliers as $supplier)
            <tr>
                <td>{{ $supplier->name }}</td>
                <td>{{ $supplier->users->count() }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html> 