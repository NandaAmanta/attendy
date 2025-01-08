<?php

use App\Consts\Action;
use App\Consts\Module;
use App\Models\Employee;
use Illuminate\Support\Facades\Auth;

if (! function_exists('logged_in_employee_has_permission')) {
    /**
     * Checks if the logged-in employee has a given permission in a given module.
     *
     * @param  Action  $action  The action to check.
     * @param  Module  $module  The module to check.
     * @return bool True if the employee has the permission, false otherwise.
     */
    function logged_in_employee_has_permission(Action $action, Module $module): bool
    {
        return Employee::query()
            ->where('id', Auth::guard('employee_web')->user()->id)
            ->firstOrFail()
            ->hasPermissionTo($action, $module);
    }
}

if (! function_exists('get_user_id_from_auth_user')) {
    /**
     * Retrieves the user ID of the authenticated user. If the authenticated user is an
     * employee, their user ID is retrieved from the `user_id` column on the `employees`
     * table. Otherwise, the `id` column of the `users` table is used.
     *
     * @return int The user ID of the authenticated user.
     */
    function get_user_id_from_auth_user(): int
    {
        return is_null(Auth::user()->user_id) ? Auth::user()->id : Auth::user()->user_id;
    }
}

if (! function_exists('haversineGreatCircleDistance')) {
    function haversine_great_circle_distance($latitudeFrom, $longitudeFrom, $latitudeTo, $longitudeTo, $radius, $earthRadius = 6371000): bool
    {
        // Convert from degrees to radians
        $latFrom = deg2rad($latitudeFrom);
        $lonFrom = deg2rad($longitudeFrom);
        $latTo = deg2rad($latitudeTo);
        $lonTo = deg2rad($longitudeTo);

        $latDelta = $latTo - $latFrom;
        $lonDelta = $lonTo - $lonFrom;

        $angle = 2 * asin(sqrt(pow(sin($latDelta / 2), 2) +
            cos($latFrom) * cos($latTo) * pow(sin($lonDelta / 2), 2)));

        $result = $angle * $earthRadius;

        return $result <= $radius;
    }
}
