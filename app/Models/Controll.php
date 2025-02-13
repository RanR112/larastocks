<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

class Controll extends Model
{
    use HasFactory, HasUuids;

    protected $table = 'controll';
    
    protected $fillable = [
        'supplier_id',
        'user_id',
        'pdf_supplier',
        'pdf_fortcast'
    ];

    public function supplier()
    {
        return $this->belongsTo(Supplier::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
