<?php

use App\Consts\Action;

return [

    'employee' => [
        Action::CREATE->value,
        Action::READ->value,
        Action::UPDATE->value,
        Action::DELETE->value,
    ],

    'attendance' => [
        Action::CREATE->value,
        Action::READ->value,
        Action::UPDATE->value,
        Action::DELETE->value,
    ],

    'leave_application' => [
        Action::CREATE->value,
        Action::READ->value,
        Action::UPDATE->value,
        Action::DELETE->value,
        Action::APPROVAL->value,
    ],

    'package' => [
        Action::CREATE->value,
        Action::READ->value,
        Action::UPDATE->value,
        Action::DELETE->value,
    ],

    'office' => [
        Action::CREATE->value,
        Action::READ->value,
        Action::UPDATE->value,
        Action::DELETE->value,
    ],

    'position' => [
        Action::CREATE->value,
        Action::READ->value,
        Action::UPDATE->value,
        Action::DELETE->value,
    ],
];
