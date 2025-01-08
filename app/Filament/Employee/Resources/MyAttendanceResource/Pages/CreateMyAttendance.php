<?php

namespace App\Filament\Employee\Resources\MyAttendanceResource\Pages;

use App\Filament\Employee\Resources\MyAttendanceResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateMyAttendance extends CreateRecord
{
    protected static string $resource = MyAttendanceResource::class;
}
