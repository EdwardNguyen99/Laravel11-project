<?php

namespace Modules\Admin\src\Http\Controllers;

use App\Http\Controllers\Controller;

class DashboardController extends Controller
{
    public function index()
    {
        // Sample statistics - replace with actual data from your models
        $stats = [
            'users' => 0,
            'courses' => 0,
            'orders' => 0,
            'revenue' => 0,
            'new_users_today' => 0,
            'new_orders_today' => 0,
            'active_courses' => 0,
        ];

        return view('admin::pages.dashboard', compact('stats'));
    }
}
