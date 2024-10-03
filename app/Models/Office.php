<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Office extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'lat',
        'lng',
        'max_radius_attendance_in_meter',
        'max_attendance_in_hour',
        'user_id',
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

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
