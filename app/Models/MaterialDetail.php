<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

class MaterialDetail extends Model
{
    use HasFactory, HasUuids;

    protected $table = 'material_details';
    protected $fillable = [
        'material_id',
        'diameter',
        'kg_coil',
    ];

    public function material()
    {
        return $this->belongsTo(Material::class);
    }
} 