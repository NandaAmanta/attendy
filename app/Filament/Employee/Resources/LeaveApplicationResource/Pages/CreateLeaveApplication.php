<?php

namespace App\Filament\Employee\Resources\LeaveApplicationResource\Pages;

use App\Consts\LeaveStatus;
use App\Filament\Employee\Resources\LeaveApplicationResource;
use Filament\Actions\Action;
use Filament\Resources\Pages\CreateRecord;

class CreateLeaveApplication extends CreateRecord
{
    protected static string $resource = LeaveApplicationResource::class;

    protected function getFormActions(): array
    {
        return [
            $this->getCreateFormAction(),
            $this->getCreateFormDraftAction(),
            $this->getCancelFormAction(),
        ];
    }

    protected function getCreateFormDraftAction(): Action
    {
        return Action::make('draft')
            ->label(__('filament-panels::resources/pages/create-record.form.actions.create.label'))
            ->color('secondary')
            ->action('createDraft');
    }

    public function createDraft()
    {
        $data = $this->form->getState();
        $data['status'] = LeaveStatus::DRAFT;
        $this->handleRecordCreation($data);
    }
}
