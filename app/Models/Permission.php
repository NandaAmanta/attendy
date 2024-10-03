<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Permission extends Model
{
    use HasFactory;

    protected $fillable = [
        'action',
        'module',
    ];

    public function positions()
    {
        return $this->belongsToMany(Position::class, 'position_permissions');
    }
}
