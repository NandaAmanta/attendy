<?php

namespace App\Filament\Employee\Resources\MyAttendanceResource\Pages;

use App\Filament\Employee\Resources\MyAttendanceResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListMyAttendances extends ListRecords
{
    protected static string $resource = MyAttendanceResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
