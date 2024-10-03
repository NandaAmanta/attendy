<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Position extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            $model->user_id = get_user_id_from_auth_user();
        });

        static::updating(function ($model) {
            $model->user_id = get_user_id_from_auth_user();
        });
    }

    public function permissions()
    {
        return $this->belongsToMany(Permission::class);
    }
}
