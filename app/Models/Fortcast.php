<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

class Fortcast extends Model
{
    use HasFactory, HasUuids;

    protected $table = 'fortcast';
    
    protected $fillable = [
        'material_id',
        'material_detail_id',
        'po',
        'control_po_id',
        'actual_receive_id',
        'balance',
        'persentase',
        'kanban'
    ];

    public function material()
    {
        return $this->belongsTo(Material::class);
    }

    public function materialDetail()
    {
        return $this->belongsTo(MaterialDetail::class);
    }

    public function controlPo()
    {
        return $this->belongsTo(ControlPo::class);
    }

    public function actualReceive()
    {
        return $this->belongsTo(ActualReceive::class);
    }
} 