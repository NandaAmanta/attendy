<?php

namespace App\Filament\Employee\Widgets;

use App\Consts\AttendanceType;
use App\Models\Attendance;
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

    public static function canView(): bool
    {
        $todayPresentOut = Attendance::query()
            ->where('employee_id', Auth::guard('employee_web')->user()->id)
            ->where('type', AttendanceType::OUT->value)
            ->where('present_at', '>=', now()->startOfDay())
            ->where('present_at', '<=', now()->endOfDay())
            ->first()
            ->present_at ?? null;

        return $todayPresentOut === null;
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
