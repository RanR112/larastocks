<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

class Roles extends Model
{
    use HasUuids;
    protected $table = 'roles';
    protected $fillable = ['name'];
    public $timestamps = false;

    public function users()
    {
        return $this->hasMany(User::class);
    }

    public function getRouteKeyName()
    {
        return 'id';
    }
}
