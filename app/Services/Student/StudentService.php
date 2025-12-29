<?php

namespace App\Services\Student;

use App\Repositories\Student\StudentRepository;

class StudentService
{
    protected StudentRepository $repository;


    public function __construct(StudentRepository $repository)
    {
        $this->repository = $repository;
    }

    /**
     */
    public function createAndAttachToGroup(array $studentData, int $groupId)
    {
        $student = $this->repository->findByNo($studentData['no']);

        if (! $student) {
            $student = $this->repository->create($studentData);
        }

        if (! $student->hasRole('student')) {
            $student->assignRole('student');
        }

        $this->repository->attachToGroup($groupId, $student->id);

        return $student;
    }
}
