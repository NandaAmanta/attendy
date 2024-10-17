<?php

namespace App\Filament\Employee\Widgets;

use App\Service\AttendanceService;
use Filament\Notifications\Notification;
use Filament\Widgets\Widget;
use Illuminate\Support\Facades\Auth;

class AttendanceAbsence extends Widget
{
    protected static bool $isLazy = false;

    protected static string $view = 'filament.employee.widgets.attendance-absence';

    protected int|string|array $columnSpan = 'full';

    protected static ?int $sort = 2;

    private AttendanceService $attendanceService;

    public function __construct()
    {
        $this->attendanceService = new AttendanceService;
    }

    public function present(array $data): void
    {
        $this->attendanceService->attendance($data);
    }

    public function isInOffice(array $data): bool
    {
        $office = Auth::guard('employee_web')->user()->office;
        $isInOffice = haversine_great_circle_distance(
            $data['lat'],
            $data['lng'],
            $office->lat,
            $office->lng,
            $office->max_radius_attendance_in_meter
        );

        if (! $isInOffice) {
            Notification::make()
                ->title('You are not in office')
                ->body('Please stay in office')
                ->danger()
                ->send();
        }

        return $isInOffice;

    }
}
