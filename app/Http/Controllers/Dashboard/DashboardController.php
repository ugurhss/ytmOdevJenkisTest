<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use App\Models\User;
use App\Services\Announcements\AnnouncementService;
use App\Services\Group\GroupService;
use App\Services\System\SystemHealthService;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;
use Inertia\Response;

class DashboardController extends Controller
{
    protected GroupService $groupService;
    protected AnnouncementService $announcementService;
    // protected SystemHealthService $systemHealthService;

    public function __construct(GroupService $groupService, AnnouncementService $announcementService)
    {
        $this->groupService = $groupService;
        $this->announcementService = $announcementService;
        // $this->systemHealthService=$systemHealthService;
    }

    public function index(): Response
    {
        $user = Auth::user();

        if ($user->hasRole('superadmin')) {
            return $this->superAdminDashboard();
        }

        if ($user->hasRole('admin')) {
            return $this->adminDashboard($user);
        }

        if ($user->hasRole('student')) {
            return $this->studentDashboard($user);
        }

        return $this->defaultDashboard();
    }


   protected function superAdminDashboard(): Response
{
    $groups = $this->groupService->getAll();
    //  $health = $this->systemHealthService->getStatus();


    $stats = [
        'total_users'    => User::count(),
        'total_admins'   => User::role('admin')->count(),
        'total_students' => User::role('student')->count(),

//   'system_health'        => $health['overall']['label'],  // "Optimal" / "Dikkat" / "Kritik"
// 'system_health_level'  => $health['overall']['level'],  // "ok" / "warning" / "critical"
    ];

    $logs = ActivityLog::query()
        ->latest()
        ->limit(20)
        ->get([
            'id',
            'event',
            'description',
            'actor_name',
            'created_at',
        ]);

    $announcements = $groups->load(['announcements' => function ($q) {
        $q->with('user')->latest()->take(5);
    }])->pluck('announcements')->flatten()->sortByDesc('created_at')->take(5)->values();

    return Inertia::render('SuperAdmin/Dashboard', [
        'stats'         => $stats,
        'groups'        => $groups,
        'logs'          => $logs,
        'recentActions' => $announcements,
    ]);
}


    protected function adminDashboard(User $user): Response
    {
        $groups = $this->groupService->getGroupsByUser($user->id);

        $stats = [
            'active_students'   => User::role('student')->count(),
            'pending_approvals' => 0,
            'recent_activity'   => 0
        ];

        $announcements = $groups->load(['announcements' => function($q) {
            $q->with('user')->latest()->take(5);
        }])->pluck('announcements')->flatten()->sortByDesc('created_at')->take(5)->values();

        $stats['recent_activity'] = $announcements->count();

        return Inertia::render('Admin/Dashboard', [
            'stats'         => $stats,
            'groups'        => $groups,
            'recentActions' => $announcements,
        ]);
    }


  protected function studentDashboard(User $user): Response
{
    $groups = $this->groupService->getGroupsByStudent($user->id);

    $recentActions = $groups->pluck('announcements')
                            ->flatten()
                            ->sortByDesc('created_at')
                            ->take(5)
                            ->values();

    return Inertia::render('User/Dashboard', [
        'user'          => $user,
        'groups'        => $groups,
        'recentActions' => $recentActions,
    ]);
}



    protected function defaultDashboard(): Response
    {
        return Inertia::render('Dashboard');
    }
}
