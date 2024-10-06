<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LeaveApplication extends Model
{
    use HasFactory;

    protected $fillable = [
        'employee_id',
        'type',
        'start_at',
        'end_at',
        'description',
        'status',
        'image_path',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            $model->employee_id = get_user_id_from_auth_user();
        });

        static::updating(function ($model) {
            $model->employee_id = get_user_id_from_auth_user();
        });
    }

    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }
}
