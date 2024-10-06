<?php

namespace App\Filament\Employee\Resources\LeaveApplicationResource\Pages;

use App\Filament\Employee\Resources\LeaveApplicationResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateLeaveApplication extends CreateRecord
{
    protected static string $resource = LeaveApplicationResource::class;
}
