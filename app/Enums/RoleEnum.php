<?php

namespace App\Enums;

enum RoleEnum: int
{
    case admin = 2;
    case user = 3;
    case super = 1;
    case debugger = 4;
}
