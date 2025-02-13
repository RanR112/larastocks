<?php

namespace App\Exports;

use App\Models\ControlPo;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class ControlPosExport implements FromCollection, WithHeadings, WithMapping
{
    protected $filters;

    public function __construct($filters = [])
    {
        $this->filters = $filters;
    }

    public function collection()
    {
        $query = ControlPo::with(['supplier', 'material', 'materialDetail', 'noPo', 'actualReceives']);
        
        if (!empty($this->filters['supplier_id'])) {
            $query->where('supplier_id', $this->filters['supplier_id']);
        }
        if (!empty($this->filters['schedule_incoming'])) {
            $query->whereDate('schedule_incoming', $this->filters['schedule_incoming']);
        }
        if (!empty($this->filters['material_receiving_status'])) {
            $query->where('material_receiving_status', $this->filters['material_receiving_status']);
        }
        if (!empty($this->filters['month'])) {
            $query->where('month', $this->filters['month']);
        }

        // Tambahkan ordering untuk bulan
        $query->orderByRaw("FIELD(month, 
            'January', 
            'February', 
            'March', 
            'April', 
            'May', 
            'June', 
            'July', 
            'August', 
            'September', 
            'October', 
            'November', 
            'December'
        )");

        return $query->get();
    }

    public function headings(): array
    {
        return [
            'PO Date',
            'Supplier',
            'Material Name',
            'Diameter',
            'STD Wire (Kg)',
            'PO No.',
            'Schedule Incoming',
            'Order Qty (Coil)',
            'Order Qty (Kg)',
            'Month',
            'Total Coil',
            'Total KG',
            'Material Receiving Status',
            'Note'
        ];
    }

    public function map($controlPo): array
    {
        return [
            $controlPo->noPo->po_date,
            $controlPo->supplier->name,
            $controlPo->material->material_name,
            $controlPo->materialDetail->diameter,
            $controlPo->materialDetail->kg_coil,
            $controlPo->noPo->po_name,
            $controlPo->schedule_incoming,
            $controlPo->qty_coil,
            $controlPo->qty_kg,
            $controlPo->month,
            $controlPo->total_coil,
            $controlPo->total_kg,
            $controlPo->material_receiving_status,
            $controlPo->notes
        ];
    }
} 