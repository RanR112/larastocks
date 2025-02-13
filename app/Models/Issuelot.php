<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

class Issuelot extends Model
{
    use HasFactory, HasUuids;

    protected $table = 'issuelot';
    
    protected $fillable = [
        'supplier_id',
        'material_id',
        'material_detail_id',
        'tag_lot',
        'qa_tag',
        'charge_no',
        'qty_kg',
        'coil_no',
        'qr_no'
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

    // Accessor untuk diameter
    public function getDiameterAttribute()
    {
        return $this->materialDetail->diameter;
    }
} 