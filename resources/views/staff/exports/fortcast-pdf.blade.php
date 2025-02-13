<!DOCTYPE html>
<html>
<head>
    <title>{{ $title }}</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
        }
        .header {
            text-align: center;
            margin-bottom: 20px;
        }
        .filters {
            margin-bottom: 20px;
            font-size: 12px;
        }
        .filter-item {
            margin-bottom: 5px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
            font-size: 12px;
        }
        th, td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }
        th {
            background-color: #f2f2f2;
            font-weight: bold;
        }
        tr:nth-child(even) {
            background-color: #f9f9f9;
        }
        .text-red {
            color: #dc2626;
        }
        .text-right {
            text-align: right;
        }
        .footer {
            margin-top: 20px;
            font-size: 10px;
            text-align: center;
            color: #666;
        }
    </style>
</head>
<body>
    <div class="header">
        <h2>{{ $title }}</h2>
        <p>Generated on {{ now()->format('d F Y H:i:s') }}</p>
    </div>

    <div class="filters">
        <div class="filter-item">
            <strong>Search:</strong> {{ $filters['search'] ?: 'None' }}
        </div>
        <div class="filter-item">
            <strong>Supplier:</strong> {{ $filters['supplier'] }}
        </div>
        <div class="filter-item">
            <strong>Month:</strong> {{ $filters['month'] }}
        </div>
    </div>

    <table>
        <thead>
            <tr>
                <th>Material Name</th>
                <th>Ø</th>
                <th>Std QTY</th>
                <th>Month</th>
                <th>PO</th>
                <th>ACTUAL</th>
                <th>BALANCE</th>
                <th>%</th>
                <th>Kanban</th>
            </tr>
        </thead>
        <tbody>
            @forelse($fortcasts as $fortcast)
                <tr>
                    <td>{{ $fortcast['material_name'] }}</td>
                    <td>{{ $fortcast['diameter'] }}</td>
                    <td class="text-right">{{ number_format($fortcast['std_qty'], 2) }}</td>
                    <td>{{ $fortcast['month'] }}</td>
                    <td class="text-right">{{ number_format($fortcast['po'], 2) }}</td>
                    <td class="text-right">{{ number_format($fortcast['actual'], 2) }}</td>
                    <td class="text-right {{ $fortcast['balance'] < 0 ? 'text-red' : '' }}">
                        {{ number_format($fortcast['balance'], 2) }}
                    </td>
                    <td class="text-right">{{ $fortcast['percentage'] }}</td>
                    <td class="text-right {{ $fortcast['kanban'] < 0 ? 'text-red' : '' }}">
                        {{ number_format($fortcast['kanban'], 0) }}
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="9" style="text-align: center;">No data available</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <div class="footer">
        <p>This report was automatically generated from the system</p>
    </div>
</body>
</html> 