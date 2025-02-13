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
                <th>In Date</th>
                <th>Supplier</th>
                <th>Delivery Number</th>
                <th>Material</th>
                <th>Ø</th>
                <th>Weight</th>
                <th>Coils</th>
                <th>PO No.</th>
                <th>Charge No.</th>
                <th>Coil No.</th>
            </tr>
        </thead>
        <tbody>
            @forelse($actualReceives as $actualReceive)
                <tr>
                    <td>{{ $actualReceive->in_date->format('d M Y') }}</td>
                    <td>{{ $actualReceive->supplier->name }}</td>
                    <td>{{ $actualReceive->delivery_number }}</td>
                    <td>{{ $actualReceive->material->material_name }}</td>
                    <td>{{ $actualReceive->materialDetail->diameter }}</td>
                    <td class="text-right">{{ number_format($actualReceive->weight, 2) }}</td>
                    <td class="text-right">{{ number_format($actualReceive->total_coil) }}</td>
                    <td>{{ $actualReceive->noPo->po_name }}</td>
                    <td>{{ $actualReceive->charge_number }}</td>
                    <td>{{ $actualReceive->coil_no }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="10" style="text-align: center;">No data available</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <div class="footer">
        <p>This report was automatically generated from the system</p>
    </div>
</body>
</html> 