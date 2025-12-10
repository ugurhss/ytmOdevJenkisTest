<?php

namespace App\Http\Controllers\Group;
use App\Http\Controllers\Controller;

use App\Http\Requests\StoreGroupRequest;
use App\Http\Requests\UpdateGroupRequest;
use App\Models\Group;
use App\Services\City\CityService;
use App\Services\Group\GroupService;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;


class GroupController extends Controller
{
    use AuthorizesRequests;
    protected GroupService $groupService;
    protected CityService $cityService;

    public function __construct(GroupService $groupService, CityService $cityService)
    {
        $this->groupService = $groupService;
        $this->cityService = $cityService;


    }



    public function grupIndex(){
    $this->authorize('viewAny', Group::class);

 $groups = $this->groupService->getAll();
return Inertia::render('Groups/Index', [
    'groups' => $groups,
]);


    }
    public function grupCreate()
    {
        $this->authorize('create', Group::class);

        $cities = $this->cityService->getAll();
        return Inertia::render('Groups/Create', [
            'cities' => $cities,
        ]);

    }


public function grupStore(StoreGroupRequest $request)
{
    $this->authorize('create', Group::class);

    $validated = $request->validated();

    $validated['user_id'] = Auth::id();

    $group = $this->groupService->create($validated);

    return redirect()
        ->route('groups.show', $group)
        ->with('success', 'Grup başarıyla oluşturuldu!');
}


   public function grupShow(int $id)
{
    $group = $this->groupService->getById($id);


    $this->authorize('view', $group);

 $students = $this->groupService->getStudents($id);

    $group->load(['city', 'university', 'faculty', 'department', 'classModel','announcements']);

    return Inertia::render('Groups/Show', [
        'group' => $group,
        'students' => $students,
    ]);
}

    public function grupEdit(int $id)
    {
        $group = $this->groupService->getById($id);


        $this->authorize('update', $group);

        $cities = $this->cityService->getAll();
            return Inertia::render('Groups/Edit', [
            'group' => $group,
            'cities' => $cities,
        ]);    }


public function grupUpdate(UpdateGroupRequest $request, int $id)
{
    $group = $this->groupService->getById($id);

    $this->authorize('update', $group);

    $validated = $request->validated();

    $this->groupService->update($id, $validated);

    return back()->with('success', 'Grup bilgileri güncellendi.');
}

     public function grupDestroy(int $id)
    {
        $group = $this->groupService->getById($id);

        $this->authorize('delete', $group);

        $this->groupService->delete($id);

        return redirect()
            ->route('dashboard')
            ->with('success', 'Grup başarıyla silindi.');
    }
}
