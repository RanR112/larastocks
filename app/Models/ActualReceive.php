<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

class ActualReceive extends Model
{
    use HasFactory, HasUuids;

    protected $table = 'actual_recive';
    
    protected $fillable = [
        'in_date',
        'supplier_id',
        'material_id',
        'material_detail_id',
        'no_po_id',
        'delivery_number',
        'weight',
        'total_coil',
        'control_po_id',
        'charge_number',
        'coil_no',
        'note'
    ];

    protected $casts = [
        'in_date' => 'date',
    ];

    public function supplier()
    {
        return $this->belongsTo(Supplier::class);
    }

    public function material()
    {
        return $this->belongsTo(Material::class);
    }

    public function materialDetail()
    {
        return $this->belongsTo(MaterialDetail::class);
    }

    public function noPo()
    {
        return $this->belongsTo(No_Po::class, 'no_po_id');
    }

    public function controlPo()
    {
        return $this->belongsTo(ControlPo::class);
    }
} 