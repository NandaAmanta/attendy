<?php

namespace App\Filament\Employee\Resources\LeaveApplicationResource\Pages;

use App\Filament\Employee\Resources\LeaveApplicationResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditLeaveApplication extends EditRecord
{
    protected static string $resource = LeaveApplicationResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
