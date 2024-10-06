<?php

namespace App\Models;

use App\Consts\Action;
use App\Consts\Module;
use Filament\Models\Contracts\FilamentUser;
use Filament\Panel;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Support\Facades\Hash;

class Employee extends Authenticatable implements FilamentUser
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'position_id',
        'office_id',
        'name',
        'email',
        'phone_number',
        'address',
        'password',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            $model->user_id = get_user_id_from_auth_user();
            $model->password = Hash::make($model->password);
        });

        static::updating(function ($model) {
            $model->user_id = get_user_id_from_auth_user();
            // check if password was changed
            if ($model->isDirty('password')) {
                $model->password = Hash::make($model->password);
            }
        });
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function office()
    {
        return $this->belongsTo(Office::class);
    }

    public function position()
    {
        return $this->belongsTo(Position::class);
    }

    public function hasPermissionTo(Action $action, Module $module): bool
    {

        return $this->position->permissions
            ->where('action', $action->value)
            ->where('module', $module->value)
            ->isNotEmpty();
    }

    public function canAccessPanel(Panel $panel): bool
    {
        return true;
    }
}
