<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

class Material extends Model
{
    use HasFactory, HasUuids;

    protected $table = 'material';
    protected $fillable = ['supplier_id', 'material_name'];

    public function details()
    {
        return $this->hasMany(MaterialDetail::class);
    }

    public function supplier()
    {
        return $this->belongsTo(Supplier::class);
    }
    public function controlPos()
    {
        return $this->hasMany(ControlPo::class);
    }

    public function actualReceives()
    {
        return $this->hasMany(ActualReceive::class);
    }
} 
