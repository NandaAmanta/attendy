<?php

namespace App\Filament\Employee\Widgets;

use App\Consts\AttendanceType;
use App\Models\Attendance;
use Filament\Support\Colors\Color;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;

class DailyAttendanceWidget extends BaseWidget
{
    protected function getStats(): array
    {
        $todayPresentIn = Attendance::query()
            ->where('employee_id', Auth::guard('employee_web')->user()->id)
            ->where('type', AttendanceType::IN->value)
            ->where('present_at', '>=', now()->startOfDay())
            ->where('present_at', '<=', now()->endOfDay())
            ->first()
            ->present_at ?? null;

        $todayPresentOut = Attendance::query()
            ->where('employee_id', Auth::guard('employee_web')->user()->id)
            ->where('type', AttendanceType::OUT->value)
            ->where('present_at', '>=', now()->startOfDay())
            ->where('present_at', '<=', now()->endOfDay())
            ->first()
            ->present_at ?? null;

        return [
            Stat::make('Today Present In', ($todayPresentIn ? ' at '.Carbon::parse($todayPresentIn)->format('h:i A') : '--:--'))
                ->color(Color::Green)
                ->icon('heroicon-s-check-circle'),

            Stat::make('Today Present Out', ($todayPresentOut ? ' at '.Carbon::parse($todayPresentOut)->format('h:i A') : '--:--'))
                ->color(Color::Green)
                ->icon('heroicon-s-check-circle'),
        ];
    }
}
