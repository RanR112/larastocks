<?php

namespace App\Exports;

use App\Models\User;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class UsersExport implements FromCollection, WithHeadings, WithMapping
{
    protected $supplierId;

    public function __construct($supplierId = null)
    {
        $this->supplierId = $supplierId;
    }

    public function collection()
    {
        $query = User::with(['role', 'supplier']);
        
        if ($this->supplierId) {
            $query->where('supplier_id', $this->supplierId);
        }

        return $query->get();
    }

    public function headings(): array
    {
        return [
            'Name',
            'Email',
            'Role',
            'Supplier'
        ];
    }

    public function map($user): array
    {
        return [
            $user->name,
            $user->email,
            $user->role->name,
            $user->supplier ? $user->supplier->name : '-'
        ];
    }
} 