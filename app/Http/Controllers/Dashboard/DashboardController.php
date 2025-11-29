<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Services\Announcements\AnnouncementService;
use App\Services\Group\GroupService;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;
use Inertia\Response;

class DashboardController extends Controller
{
    protected GroupService $groupService;
    protected AnnouncementService $announcementService;

    public function __construct(GroupService $groupService, AnnouncementService $announcementService)
    {
        $this->groupService = $groupService;
        $this->announcementService = $announcementService;
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

    /**
     * SUPERADMIN → TÜM GRUPLAR
     */
    protected function superAdminDashboard(): Response
    {
        $groups = $this->groupService->getAll();

        $stats = [
            'total_users'    => User::count(),
            'total_admins'   => User::role('admin')->count(),
            'total_students' => User::role('student')->count(),
            'system_health'  => 'Optimal'
        ];

        // Son aktiviteler → tüm gruplardan son 5 duyuru
        $announcements = $groups->load(['announcements' => function($q) {
            $q->with('user')->latest()->take(5);
        }])->pluck('announcements')->flatten()->sortByDesc('created_at')->take(5)->values();

        return Inertia::render('SuperAdmin/Dashboard', [
            'stats'         => $stats,
            'groups'        => $groups,
            'recentActions' => $announcements,
        ]);
    }

    /**
     * ADMIN → KENDİ GRUPLARI
     */
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

    /**
     * STUDENT → ÜYE OLDUĞU GRUPLAR
     */
  protected function studentDashboard(User $user): Response
{
    $groups = $this->groupService->getGroupsByStudent($user->id);

    // recentActions zaten 'announcements.user' eager load edildiği için direkt kullanabiliriz
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


    /**
     * DEFAULT
     */
    protected function defaultDashboard(): Response
    {
        return Inertia::render('Dashboard');
    }
}
