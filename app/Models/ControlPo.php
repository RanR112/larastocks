<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use App\Models\No_Po;


class ControlPo extends Model
{
    use HasFactory, HasUuids;

    protected $table = 'control_po';
    protected $fillable = [
        'supplier_id',
        'material_id',
        'material_detail_id',
        'no_po_id',
        'schedule_incoming',
        'qty_coil',
        'qty_kg',
        'month',
        'material_receiving_status'
    ];

    protected $casts = [
        'schedule_incoming' => 'date'
    ];

    // Accessor untuk total_coil
    public function getTotalCoilAttribute()
    {
        return $this->actualReceives()->sum('total_coil') ?? '0';
    }

    // Accessor untuk total_kg
    public function getTotalKgAttribute()
    {
        return $this->actualReceives()->sum('weight') ?? '0';
    }

    // Boot method untuk mengatur status berdasarkan total
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($controlPo) {
            if (!$controlPo->month) {
                $noPo = No_Po::find($controlPo->no_po_id);
                if ($noPo) {
                    $controlPo->month = date('F', strtotime($noPo->po_date));
                }
            }
            $controlPo->material_receiving_status = 'waiting';
        });

        // Observer untuk update status berdasarkan actual_receive
        static::updating(function ($controlPo) {
            $totalCoil = $controlPo->total_coil;
            
            // Update status berdasarkan perbandingan qty
            if ($totalCoil == $controlPo->qty_coil) {
                $controlPo->material_receiving_status = 'received';
            } else {
                $controlPo->material_receiving_status = 'waiting';
            }
        });
    }

    // Relasi
    public function supplier()
    {
        return $this->belongsTo(Supplier::class);
    }

    public function material()
    {
        return $this->belongsTo(Material::class);
    }

    public function noPo()
    {
        return $this->belongsTo(No_Po::class, 'no_po_id');
    }

    public function materialDetail()
    {
        return $this->belongsTo(MaterialDetail::class);
    }

    public function actualReceives()
    {
        return $this->hasMany(ActualReceive::class, 'control_po_id');
    }

    public function getNotesAttribute()
    {
        return $this->actualReceives()->pluck('note')->filter()->implode(', ');
    }
} 