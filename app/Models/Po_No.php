<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Po_No extends Model
{
    public function supplier()
    {
        return $this->belongsTo(Supplier::class);
    }

    public function materials()
    {
        return $this->hasMany(Material::class);
    }
}
