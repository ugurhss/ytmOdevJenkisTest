<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;

class DashboardController extends Controller
{
    public function index()
    {
       $user = auth()->user();



      if ($user->hasRole('superadmin')) {
        return $this->superAdminDashboard();
      }

        if ($user->hasRole('admin')) {
            return $this->adminDashboard();
        }

        if ($user->hasRole('student')) {
            return $this->studentDashboard();
        }

        return $this->defaultDashboard();
    }
    protected function superAdminDashboard()
    {
        $stats = [
            'total_users' => \App\Models\User::count(),
            'total_admins' => \App\Models\User::role('admin')->count(),
            'total_students' => \App\Models\User::role('student')->count(),
            'system_health' => 'Optimal'
        ];

        return view('dashboard.superAdmin.dashboard', compact('stats'));
    }

    protected function adminDashboard()
    {
        $stats = [
            'active_students' => \App\Models\User::role('student')->count(),
            'pending_approvals' => 12,
            'recent_activity' => 24
        ];

        return view('dashboard.admin.dashboard', compact('stats'));
    }

    protected function studentDashboard()
    {
        $studentData = [
            'completed_courses' => 5,
            'ongoing_courses' => 3,
            'average_grade' => 85,
            'next_assignment' => 'Matematik Ödevi - 15.12.2024'
        ];

        return view('dashboard.user.dashboard', compact('studentData'));
    }

    protected function defaultDashboard()
    {
        return view('dashboard'); // Eğer resources/views/dashboard.blade.php varsa
    }
}
