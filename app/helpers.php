<?php

use App\Consts\Action;
use App\Consts\Module;
use App\Models\Employee;
use Illuminate\Support\Facades\Auth;

if (! function_exists('logged_in_employee_has_permission')) {
    function logged_in_employee_has_permission(Action $action, Module $module): bool
    {
        return Employee::query()
            ->where('user_id', Auth::user()->id)
            ->firstOrFail()
            ->hasPermissionTo($action, $module);
    }
}
