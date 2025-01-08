<?php

namespace App\Filament\Employee\Resources\MyAttendanceResource\Pages;

use App\Filament\Employee\Resources\MyAttendanceResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditMyAttendance extends EditRecord
{
    protected static string $resource = MyAttendanceResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
