<?php

namespace Modules\Admin\src\Http\Controllers;

use App\Http\Controllers\Controller;
use Modules\User\src\Repositories\UserRepository;

class DashboardController extends Controller
{
    protected $userRepository;

    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    public function index()
    {
        $users = $this->userRepository->all();
        $usersCount = $users->count();
        $newUsersToday = $this->userRepository->getNewUsersToday();
        // Sample statistics - replace with actual data from your models
        $stats = [
            'users' => $usersCount,
            'courses' => 0,
            'orders' => 0,
            'revenue' => 0,
            'new_users_today' => $newUsersToday,
            'new_orders_today' => 0,
            'active_courses' => 0,
        ];

        $recentUsers = $this->userRepository->getRecentUsers(10);
        return view('admin::pages.dashboard', compact('stats', 'recentUsers'));
    }
}
