<?php

namespace App\Exports;

use App\Models\Material;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class MaterialsExport implements FromCollection, WithHeadings, WithMapping
{
    protected $supplierId;

    public function __construct($supplierId = null)
    {
        $this->supplierId = $supplierId;
    }

    public function collection()
    {
        $query = Material::with(['supplier', 'details']);
        
        if ($this->supplierId) {
            $query->where('supplier_id', $this->supplierId);
        }

        return $query->get();
    }

    public function headings(): array
    {
        return [
            'Material Name',
            'Supplier',
            'Specifications'
        ];
    }

    public function map($material): array
    {
        return [
            $material->material_name,
            $material->supplier->name,
            $material->details->map(function($detail) {
                return $detail->diameter . ' - ' . $detail->kg_coil . 'kg';
            })->join(', ')
        ];
    }
} 