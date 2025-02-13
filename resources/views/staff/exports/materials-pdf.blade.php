<!DOCTYPE html>
<html>
<head>
    <title>Materials List</title>
    <style>
        body {
            font-family: Arial, sans-serif;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        th, td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }
        th {
            background-color: #f2f2f2;
        }
        h1 {
            text-align: center;
            color: #333;
        }
        .filter-info {
            margin-bottom: 20px;
            color: #666;
            font-style: italic;
        }
    </style>
</head>
<body>
    <h1>Materials List</h1>
    
    @if($supplier)
        <div class="filter-info">
            Filtered by Supplier: {{ $supplier->name }}
        </div>
    @endif

    <table>
        <thead>
            <tr>
                <th>Material Name</th>
                <th>Supplier</th>
                <th>Specifications</th>
            </tr>
        </thead>
        <tbody>
            @foreach($materials as $material)
            <tr>
                <td>{{ $material->material_name }}</td>
                <td>{{ $material->supplier->name }}</td>
                <td>
                    @foreach($material->details as $detail)
                        {{ $detail->diameter }} - {{ $detail->kg_coil }}kg
                        @if(!$loop->last), @endif
                    @endforeach
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html> 