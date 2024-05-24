<?php

namespace App\Http\Controllers;

use App\Http\Helpers\RequestHelper;
use App\Http\Resources\UserCollection;
use App\Models\User;
use Illuminate\Http\Request;

/**
 * Dashboard
 */
class DashboardController extends Controller
{
    public function __invoke(Request $request): UserCollection
    {
        $users = User::query();
        return (new UserCollection($users->paginate(RequestHelper::limit($request))))
            ->additional([
                'total_users' => $users->count(),
            ]);
    }
}
