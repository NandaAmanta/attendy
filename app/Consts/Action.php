<?php

namespace App\Consts;

enum Action: string
{
    case CREATE = 'create';
    case READ = 'read';
    case UPDATE = 'update';
    case DELETE = 'delete';
    case APPROVAL = 'approval';
}
