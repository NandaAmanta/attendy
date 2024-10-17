<?php

namespace App\Filament\Owner\Resources\AttendanceResource\Pages;

use App\Filament\Owner\Resources\AttendanceResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateAttendance extends CreateRecord
{
    protected static string $resource = AttendanceResource::class;
}
