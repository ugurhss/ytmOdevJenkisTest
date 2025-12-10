<?php

namespace App\Services\Student;

use App\Models\User;
use App\Repositories\Student\StudentRepository;
use App\Services\Activity\ActivityLogService;
use Illuminate\Support\Facades\Auth;

class StudentService
{
       protected StudentRepository $repository;
    protected ActivityLogService $activityLogService;


    public function __construct(
        StudentRepository $repository,
        ActivityLogService $activityLogService
    ) {
        $this->repository = $repository;
        $this->activityLogService = $activityLogService;
    }



  public function createAndAttachToGroup(array $studentData, int $groupId): User
    {

        $student = $this->repository->createAndAttach($studentData, $groupId);

        if (! $student->hasRole('student')) {
            $student->assignRole('student');
        }


        $actor = Auth::user();
        $this->activityLogService->logStudentAddedToGroup($groupId, $student, $actor);

        return $student;
    }
}
