<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Attendance extends Model
{
    use HasFactory;

    protected $fillable = [
        'employee_id',
        'type',
        'present_at',
        'note',
        'is_ontime',
        'is_in_office',
        'lat',
        'lng',
        'image_path',
    ];

    protected $casts = [
        'is_ontime' => 'boolean',
        'is_in_office' => 'boolean',
    ];

    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }
}
