<?php

namespace App\Http\Controllers;

use App\Http\Resources\DashboardResource;
use App\Models\User;

/**
 * Dashboard
 */
class DashboardController extends Controller
{
    public function __invoke(): DashboardResource
    {
        return new DashboardResource(User::all());
    }
}
