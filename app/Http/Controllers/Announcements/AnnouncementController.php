<?php

namespace App\Http\Controllers\Announcements;

use App\Http\Controllers\Controller;
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

    /**
     * 📜 Duyuru listesi
     * Superadmin tüm duyuruları, Admin sadece kendi gruplarındaki duyuruları görür.
     */
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

    /**
     * 🆕 Yeni duyuru oluşturma formu
     */
    public function create()
    {
        $this->authorize('create', GroupAnnouncement::class);

        $user = auth()->user();

        // Servisten grupları al
        if ($user->hasRole('superadmin')) {
            $groups = $this->announcementService->getIndexData($user, request())['groups'];
        } else {
            $groups = $this->announcementService->getIndexData($user, request())['groups'];
        }

        return Inertia::render('Announcements/Create', [
            'groups' => $groups,
        ]);
    }

    /**
     * 💾 Duyuru kaydetme
     */
    public function store(Request $request)
    {
        $this->authorize('create', GroupAnnouncement::class);

        $user = auth()->user();

        $validated = $request->validate([
            'group_id' => 'required|exists:groups,id',
            'title'    => 'required|string|max:255',
            'content'  => 'required|string',
        ], [
            'group_id.required' => 'Grup seçimi zorunludur.',
            'group_id.exists'   => 'Seçilen grup bulunamadı.',
            'title.required'    => 'Başlık zorunludur.',
            'title.max'         => 'Başlık en fazla 255 karakter olabilir.',
            'content.required'  => 'İçerik zorunludur.',
        ]);

        $this->announcementService->createWithRelations($validated, $user);

        return redirect()
            ->route('announcements.index')
            ->with('success', 'Duyuru başarıyla oluşturuldu.');
    }

    /**
     * 👁️ Duyuru detayı gösterme
     */
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

    /**
     * ✏️ Duyuru düzenleme formu
     */
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

    /**
     * 🔁 Duyuru güncelleme
     */
    public function update(Request $request, $id)
    {
        $announcement = $this->announcementService->findById($id);
        $this->authorize('update', $announcement);

        $validated = $request->validate([
            'group_id' => 'required|exists:groups,id',
            'title'    => 'required|string|max:255',
            'content'  => 'required|string',
        ], [
            'group_id.required' => 'Grup seçimi zorunludur.',
            'group_id.exists'   => 'Seçilen grup bulunamadı.',
            'title.required'    => 'Başlık zorunludur.',
            'title.max'         => 'Başlık en fazla 255 karakter olabilir.',
            'content.required'  => 'İçerik zorunludur.',
        ]);

        $this->announcementService->updateWithRelations($id, $validated);

        return redirect()
            ->route('announcements.index')
            ->with('success', 'Duyuru başarıyla güncellendi.');
    }

    /**
     * 🗑️ Duyuru silme
     */
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
