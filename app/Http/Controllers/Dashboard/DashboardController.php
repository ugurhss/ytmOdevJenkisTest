<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;
use Inertia\Response;

class DashboardController extends Controller
{
    /**
     * Kullanıcıyı rolüne göre ilgili dashboard'a yönlendirir.
     */
    public function index(): Response
    {
        $user = Auth::user();

        if ($user->hasRole('superadmin')) {
            return $this->superAdminDashboard();
        }

        if ($user->hasRole('admin')) {
            return $this->adminDashboard();
        }

        if ($user->hasRole('student')) {
            return $this->studentDashboard($user);
        }

        return $this->defaultDashboard();
    }

    /**
     * Super Admin Dashboard
     */
    protected function superAdminDashboard(): Response
    {
        $stats = [
            'total_users' => User::count(),
            'total_admins' => User::role('admin')->count(),
            'total_students' => User::role('student')->count(),
            'system_health' => 'Optimal'
        ];

        return Inertia::render('SuperAdmin/Dashboard', [
            'stats' => $stats
        ]);
    }

    /**
     * Admin Dashboard
     */
    protected function adminDashboard(): Response
    {
        $stats = [
            'active_students' => User::role('student')->count(),
            'pending_approvals' => 0,
            'recent_activity' => 0
        ];

        return Inertia::render('Admin/Dashboard', [
            'stats' => $stats
        ]);
    }

    /**
     * Student Dashboard
     */
    protected function studentDashboard(User $user): Response
    {
        $user->load('groups.announcements.user');

        return Inertia::render('User/Dashboard', [
            'user' => $user
        ]);
    }

    /**
     * Varsayılan Dashboard
     */
    protected function defaultDashboard(): Response
    {

        return Inertia::render('Dashboard');
    }
}
