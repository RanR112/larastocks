<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Concerns\HasUuids;


class Fraction extends Model
{
    use HasFactory, HasUuids;

    protected $table = 'fraction';
    protected $fillable = ['location_code', 'img', 'plat_number'];
}
