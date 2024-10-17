<?php

namespace App\Filament\Employee\Resources\AttendanceResource\Pages;

use App\Filament\Employee\Resources\AttendanceResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateAttendance extends CreateRecord
{
    protected static string $resource = AttendanceResource::class;
}
