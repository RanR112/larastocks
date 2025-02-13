<?php

namespace App\Exports;

use App\Models\ActualReceive;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Illuminate\Support\Collection;

class ActualReceiveExport implements FromCollection, WithHeadings
{
    protected $request;

    public function __construct($request)
    {
        $this->request = $request;
    }

    private function getFilteredQuery()
    {
        $query = ActualReceive::with(['supplier', 'material', 'materialDetail', 'noPo', 'controlPo'])
            ->latest();

        // Filter berdasarkan pencarian
        if ($this->request->search) {
            $search = $this->request->search;
            $query->where(function($q) use ($search) {
                $q->where('delivery_number', 'like', "%{$search}%")
                  ->orWhereHas('material', function($q) use ($search) {
                      $q->where('material_name', 'like', "%{$search}%");
                  });
            });
        }

        // Filter berdasarkan supplier
        if ($this->request->supplier_id) {
            $query->where('supplier_id', $this->request->supplier_id);
        }

        // Filter berdasarkan bulan
        if ($this->request->month) {
            $query->whereHas('controlPo', function($q) {
                $q->where('month', $this->request->month);
            });
        }

        return $query;
    }

    public function collection()
    {
        $query = $this->getFilteredQuery();
        $actualReceives = $query->get();

        return new Collection($actualReceives->map(function ($item) {
            return [
                'In Date' => $item->in_date->format('d M Y'),
                'Supplier' => $item->supplier->name,
                'Delivery Number' => $item->delivery_number,
                'Material' => $item->material->material_name,
                'Diameter' => $item->materialDetail->diameter,
                'Weight' => number_format($item->weight, 2),
                'Total Coil' => number_format($item->total_coil),
                'PO Number' => $item->noPo->po_name,
                'Charge Number' => $item->charge_number,
                'Coil Number' => $item->coil_no,
                'Note' => $item->note
            ];
        }));
    }

    public function headings(): array
    {
        return [
            'In Date',
            'Supplier',
            'Delivery Number',
            'Material',
            'Diameter',
            'Weight',
            'Total Coil',
            'PO Number',
            'Charge Number',
            'Coil Number',
            'Note'
        ];
    }
} 