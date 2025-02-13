<!DOCTYPE html>
<html>
<head>
    <title>Control PO List</title>
    <style>
        body { font-family: Arial, sans-serif; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; font-size: 12px; }
        th, td { border: 1px solid #ddd; padding: 5px; text-align: left; }
        th { background-color: #f2f2f2; }
        h1 { text-align: center; color: #333; }
        .filter-info { margin-bottom: 20px; color: #666; font-style: italic; }
    </style>
</head>
<body>
    <h1>Control PO List</h1>
    
    @if(!empty($filters))
    <div class="filter-info">
        Filters:
        @if(!empty($filters['supplier_id']))
            Supplier: {{ \App\Models\Supplier::find($filters['supplier_id'])->name }},
        @endif
        @if(!empty($filters['schedule_incoming']))
            Schedule: {{ $filters['schedule_incoming'] }},
        @endif
        @if(!empty($filters['material_receiving_status']))
            Status: {{ $filters['material_receiving_status'] }},
        @endif
        @if(!empty($filters['month']))
            Month: {{ $filters['month'] }}
        @endif
    </div>
    @endif

    <table>
        <thead>
            <tr>
                <th>PO Date</th>
                <th>Supplier</th>
                <th>Material Name</th>
                <th>Diameter</th>
                <th>STD Wire (Kg)</th>
                <th>PO No.</th>
                <th>Schedule</th>
                <th>Order Qty (Coil)</th>
                <th>Order Qty (Kg)</th>
                <th>Month</th>
                <th>Total Coil</th>
                <th>Total KG</th>
                <th>Status</th>
                <th>Note</th>
            </tr>
        </thead>
        <tbody>
            @foreach($controlPos as $controlPo)
            <tr>
                <td>{{ $controlPo->noPo->po_date }}</td>
                <td>{{ $controlPo->supplier->name }}</td>
                <td>{{ $controlPo->material->material_name }}</td>
                <td>{{ $controlPo->materialDetail->diameter }}</td>
                <td>{{ $controlPo->materialDetail->kg_coil }}</td>
                <td>{{ $controlPo->noPo->po_name }}</td>
                <td>{{ $controlPo->schedule_incoming }}</td>
                <td>{{ $controlPo->qty_coil }}</td>
                <td>{{ $controlPo->qty_kg }}</td>
                <td>{{ $controlPo->month }}</td>
                <td>{{ $controlPo->total_coil }}</td>
                <td>{{ $controlPo->total_kg }}</td>
                <td>{{ $controlPo->material_receiving_status }}</td>
                <td>{{ $controlPo->notes }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html> 