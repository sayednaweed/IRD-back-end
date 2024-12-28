<?php

namespace App\Enums;

enum PermissionEnum: string
{
    case dashboard = "dashboard";
    case logs = "logs";
    case reports = "reports";
    case settings = "settings";
    case users = "users";
    case audit = "audit";
    case ngo = "ngo";
    case projects = "projects";
}
