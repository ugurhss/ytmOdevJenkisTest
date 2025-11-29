<?php

namespace App\Services\Group;

use App\Models\Group;
use App\Repositories\Group\GroupRepository;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class GroupService
{
    protected GroupRepository $repository;


        use AuthorizesRequests;

    public function __construct(GroupRepository $repository)
    {
        $this->repository = $repository;
    }


    public function getAll()
    {

        return $this->repository->all();
    }


    public function getById(int $id)
    {
        return $this->repository->find($id);
    }


    public function create(array $data)
    {

        return $this->repository->create($data);
    }


    public function update(int $id, array $data)
    {
        return $this->repository->update($id, $data);
    }


    public function delete(int $id)
    {
        return $this->repository->delete($id);
    }

      public function getStudents(int $groupId)
    {
        return $this->repository->getStudents($groupId);
    }


    public function getGroupsByUser(int $userId)
    {
        return $this->repository->getGroupsByUserId($userId);
    }

    public function getGroupsByStudent(int $studentId)
{
    return $this->repository->getGroupsByStudentId($studentId);
}

}
