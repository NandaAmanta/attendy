<?php

namespace App\Filament\Owner\Resources\EmployeeResource\Pages;

use App\Filament\Owner\Resources\EmployeeResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateEmployee extends CreateRecord
{
    protected static string $resource = EmployeeResource::class;
}
