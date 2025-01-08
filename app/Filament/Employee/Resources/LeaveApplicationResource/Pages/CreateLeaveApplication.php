<?php

namespace App\Filament\Employee\Resources\LeaveApplicationResource\Pages;

use App\Consts\LeaveStatus;
use App\Filament\Employee\Resources\LeaveApplicationResource;
use Filament\Actions\Action;
use Filament\Resources\Pages\CreateRecord;
use Filament\Support\Colors\Color;

class CreateLeaveApplication extends CreateRecord
{
    protected static string $resource = LeaveApplicationResource::class;

    protected function getFormActions(): array
    {
        return [
            $this->getCreateFormAction()
                ->color(Color::Green)
                ->label('Submit'),
            $this->getCreateFormDraftAction(),
            $this->getCancelFormAction(),
        ];
    }

    protected function getCreateFormDraftAction(): Action
    {
        return Action::make('draft')
            ->label('Draft')
            ->action('createDraft');
    }

    public function createDraft()
    {
        $data = $this->form->getState();
        $data['status'] = LeaveStatus::DRAFT;
        $this->handleRecordCreation($data);
    }
}
