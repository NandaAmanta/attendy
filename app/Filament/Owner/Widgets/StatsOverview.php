<?php

namespace App\Filament\Owner\Widgets;

use App\Models\Employee;
use App\Models\Office;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class StatsOverview extends BaseWidget
{
    protected function getStats(): array
    {

        return [
            Stat::make('Total Employees', Employee::query()->count()),
            Stat::make('Total Offices', Office::query()->count()),
            // Stat::make('Total Attendance', Attendance::query()->count()),
        ];
    }
}
