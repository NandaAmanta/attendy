<?php

namespace App\Filament\Employee\Resources\LeaveApplicationResource\Pages;

use App\Consts\LeaveStatus;
use App\Filament\Employee\Resources\LeaveApplicationResource;
use App\Models\LeaveApplication;
use Filament\Actions;
use Filament\Actions\Action;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\EditRecord;

class EditLeaveApplication extends EditRecord
{
    protected static string $resource = LeaveApplicationResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Action::make('Submit')
                ->action(function ($record) {
                    $record->status = LeaveStatus::WAITING_FOR_APPROVAL->value;
                    $record->save();

                    Notification::make()
                        ->title('Success to submit the leave aplication')
                        ->body('Please wait a moment until your aplication approved')
                        ->send();
                })
                ->hidden(fn (LeaveApplication $record) => $record->status !== LeaveStatus::DRAFT->value),
            Actions\DeleteAction::make(),
        ];
    }
}
