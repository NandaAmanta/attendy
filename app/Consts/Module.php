<?php

namespace App\Consts;

enum Module: string
{
    case OFFICE = 'office';
    case POSITION = 'position';
    case PACKAGE = 'package';
    case ATTENDANCE = 'attendance';
    case LEAVE_APPLICATION = 'leave_application';
    case EMPLOYEE = 'employee';
}
