<?php

namespace App\Service;

use App\Consts\AttendanceType;
use App\Models\Attendance;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class AttendanceService
{
    public function __construct() {}

    public function attendance($data): void
    {
        Attendance::create([
            'employee_id' => Auth::guard('employee_web')->user()->id,
            'type' => $this->getCurrentAttendanceType()->value,
            'present_at' => now(),
            'is_in_office' => $this->isInOffice($data['lat'], $data['lng']),
            'is_ontime' => $this->isOntime(),
            'lat' => $data['lat'],
            'lng' => $data['lng'],
            'image_path' => $this->storeSelfie($data['image_path']),
            'note' => $data['note'] ?? '',
        ]);
    }

    private function storeSelfie($selfieFile): string
    {
        // remove data:image/png;base64,
        $image = str_replace('data:image/png;base64,', '', $selfieFile);
        $image = str_replace(' ', '+', $image);

        $filename = Str::slug(Auth::user()->name).'_'.time().'.png';

        $selfiePath = 'selfie/'.$filename;
        Storage::put('public/'.$selfiePath, base64_decode($image));

        return $selfiePath;
    }

    private function isInOffice($lat, $lng): bool
    {
        $office = Auth::guard('employee_web')->user()->office;

        return haversine_great_circle_distance(
            $lat,
            $lng,
            $office->lat,
            $office->lng,
            $office->max_radius_attendance_in_meter
        );
    }

    /**
     * Gets the current attendance type.
     *
     * If the last attendance is an 'in' attendance, the current attendance type is 'out'.
     * Otherwise, the current attendance type is 'in'.
     */
    private function getCurrentAttendanceType(): AttendanceType
    {
        $employee = Auth::guard('employee_web')->user();
        $lastAttendance = Attendance::query()
            ->where('employee_id', $employee->id)
            ->latest()
            ->first();
        $type = AttendanceType::IN;
        if ($lastAttendance && $lastAttendance->type == AttendanceType::IN->value) {
            $type = AttendanceType::OUT;
        }

        return $type;
    }

    /**
     * Checks if the current time is within the office's allowed attendance hours.
     *
     * @return bool true if the current time is within the allowed hours, false otherwise.
     */
    private function isOntime(): bool
    {

        $officeMaxAttendanceHour = Auth::guard('employee_web')->user()->office->max_attendance_in_hour ?? null;

        return $officeMaxAttendanceHour ? now()->diffInHours(now()->startOfDay()) < $officeMaxAttendanceHour : true;
    }
}
