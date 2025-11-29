<?php

namespace App\Http\Controllers\Student;
use App\Http\Controllers\Controller;

use App\Models\Group;
use App\Models\Student;
use App\Services\Group\GroupService;
use App\Services\Student\StudentService;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rules;
use Inertia\Inertia;

class StudentController extends Controller
{
    use AuthorizesRequests;

    protected StudentService $studentService;
    protected GroupService $groupService;


    public function __construct(StudentService $studentService, GroupService $groupService)
    {
        $this->studentService = $studentService;
        $this->groupService = $groupService;


    }
public function studentCreate(int $groupId)
{
    $group = $this->groupService->getById($groupId);


    // dd([
    //     'authenticated_user' => Auth::user(),
    //     'user_roles' => Auth::user()->getRoleNames(),
    //     'hasRole_superadmin' => Auth::user()->hasRole('superadmin'),
    //     'group' => $group,
    //     'group_user_id' => $group->user_id ?? null,
    // ]);

        $this->authorize('create', Group::class);

    return Inertia::render('Students/Create', [
        'group' => $group,
    ]);
}






    public function studentStore(Request $request, int $groupId)
    {
        $group = $this->groupService->getById($groupId);

        $this->authorize('create', Group::class);

        $validated = $request->validate([
            'name'     => ['required', 'string', 'max:255'],
            'email'    => ['required', 'email', 'unique:users,email'],
            'password' => ['required', Rules\Password::defaults()],
        ]);

        $this->studentService->createAndAttachToGroup($validated, $groupId);
        return back()->with('success', 'Öğrenci başarıyla eklendi!');

    }


    public function studentIndex(int $groupId)
    {
        $group = $this->groupService->getById($groupId);

    $this->authorize('view', $group);

        $students = $this->groupService->getStudents($groupId);


  return Inertia::render('Students/Index', [
        'group' => $group,
        'students' => $students,
    ]);

}


    public function studentDestroy(int $groupId, int $studentId)
    {
        $group = $this->groupService->getById($groupId);

        $this->authorize('delete', $group);

        $group->students()->detach($studentId);

        return back()->with('success', 'Öğrenci gruptan Cıkarildi.');
    }
}
