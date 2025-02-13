<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class No_Po extends Model
{
    use HasUuids, HasFactory;

    protected $table = 'po_no';
    protected $fillable = ['po_date', 'po_name'];

    public $timestamps = false;

    public function getRouteKeyName()
    {
        return 'id';
    }
}
