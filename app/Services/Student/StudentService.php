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
        $student = $this->repository->createAndAttach($studentData, $groupId);


        $student->assignRole('Student');

        return $student;
    }
}
