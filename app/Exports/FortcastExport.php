<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use App\Http\Controllers\Staff\FrotcastController;
use Illuminate\Support\Collection;

class FortcastExport implements FromCollection, WithHeadings
{
    protected $request;

    public function __construct($request)
    {
        $this->request = $request;
    }

    public function collection()
    {
        $controller = new FrotcastController();
        $data = $controller->getFortcastData($this->request);

        return new Collection($data->map(function ($item) {
            return [
                'Material Name' => $item['material_name'],
                'Diameter' => $item['diameter'],
                'Std QTY' => $item['std_qty'],
                'Month' => $item['month'],
                'PO' => $item['po'],
                'ACTUAL' => $item['actual'],
                'BALANCE' => $item['balance'],
                'Percentage' => $item['percentage'],
                'Kanban Balance' => $item['kanban']
            ];
        }));
    }

    public function headings(): array
    {
        return [
            'Material Name',
            'Diameter',
            'Std QTY',
            'Month',
            'PO',
            'ACTUAL',
            'BALANCE',
            'Percentage',
            'Kanban Balance'
        ];
    }
} 