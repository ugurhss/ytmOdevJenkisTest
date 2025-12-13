<?php

namespace App\Repositories\Student;

use App\Models\User;
use App\Repositories\Group\GroupRepository;
use Illuminate\Support\Facades\Hash;

class StudentRepository
{
    protected User $user;
    protected GroupRepository $groupRepository;

    public function __construct(User $user, GroupRepository $groupRepository)
    {
        $this->user = $user;
        $this->groupRepository = $groupRepository;
    }

     public function create(array $data)
    {
        return $this->user->create([
            'name'     => $data['name'],
            'no'       => $data['no'],
            'email'    => $data['email'],
            'password' => Hash::make($data['password']),
        ]);
    }

    public function attachToGroup(int $groupId, int $studentId)
    {
        $group = $this->groupRepository->find($groupId);

        if (!$group->students()->where('user_id', $studentId)->exists()) {
            $group->students()->attach($studentId);
        }
    }


    public function createAndAttach(array $data, int $groupId)
    {
        $student = $this->create($data);
        $this->attachToGroup($groupId, $student->id);

        return $student;
    }
}
