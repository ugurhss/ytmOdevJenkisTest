<?php

namespace App\Http\Controllers\Announcements;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreAnnouncementRequest;
use App\Http\Requests\UpdateAnnouncementRequest;
use App\Models\Group;
use App\Models\GroupAnnouncement;
use App\Services\Announcements\AnnouncementService;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\Request;
use Inertia\Inertia;

class AnnouncementController extends Controller
{
    use AuthorizesRequests;

    protected AnnouncementService $announcementService;

    public function __construct(AnnouncementService $announcementService)
    {
        $this->announcementService = $announcementService;
    }


    public function index(Request $request)
    {
        $this->authorize('viewAny', GroupAnnouncement::class);

        $user = auth()->user();

        $data = $this->announcementService->getIndexData($user, $request);

        return Inertia::render('Announcements/Index', [
            'announcements' => $data['announcements'],
            'groups'        => $data['groups'],
            'filters'       => $data['filters'],
            'can'           => [
                'create' => $user->can('create', GroupAnnouncement::class),
            ],
        ]);
    }


    public function create()
    {
        $this->authorize('create', GroupAnnouncement::class);

        $user = auth()->user();


        if ($user->hasRole('superadmin')) {
            $groups = $this->announcementService->getIndexData($user, request())['groups'];
        } else {
            $groups = $this->announcementService->getIndexData($user, request())['groups'];
        }

        return Inertia::render('Announcements/Create', [
            'groups' => $groups,
        ]);
    }


  public function store(StoreAnnouncementRequest $request)
{
    $this->authorize('create', GroupAnnouncement::class);

    $user = auth()->user();

    $validated = $request->validated();

    $this->announcementService->createWithRelations($validated, $user);

    return redirect()
        ->route('announcements.index')
        ->with('success', 'Duyuru başarıyla oluşturuldu.');
}


    public function show($id)
    {
        $announcement = $this->announcementService->findById($id);

        $this->authorize('view', $announcement);

        return Inertia::render('Announcements/Show', [
            'announcement' => $announcement,
            'can' => [
                'update' => auth()->user()->can('update', $announcement),
                'delete' => auth()->user()->can('delete', $announcement),
            ],
        ]);
    }


    public function edit($id)
    {
        $announcement = $this->announcementService->findById($id);

        $this->authorize('update', $announcement);

        $user = auth()->user();
        $groups = $this->announcementService->getIndexData($user, request())['groups'];

        return Inertia::render('Announcements/Edit', [
            'announcement' => $announcement,
            'groups'       => $groups,
        ]);
    }

public function update(UpdateAnnouncementRequest $request, $id)
{
    $announcement = $this->announcementService->findById($id);
    $this->authorize('update', $announcement);

    $validated = $request->validated();

    $this->announcementService->updateWithRelations($id, $validated);

    return redirect()
        ->route('announcements.index')
        ->with('success', 'Duyuru başarıyla güncellendi.');
}


    public function destroy($id)
    {
        $announcement = $this->announcementService->findById($id);
        $this->authorize('delete', $announcement);

        $this->announcementService->delete($id);

        return redirect()
            ->route('announcements.index')
            ->with('success', 'Duyuru başarıyla silindi.');
    }
}
