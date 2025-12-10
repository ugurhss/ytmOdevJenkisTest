<?php

namespace App\Services\Group;

use App\Models\Group;
use App\Repositories\Group\GroupRepository;
use App\Services\Activity\ActivityLogService;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Support\Facades\Auth;

class GroupService
{
    use AuthorizesRequests;

    protected GroupRepository $repository;
    protected ActivityLogService $activityLogService;

    public function __construct(
        GroupRepository $repository,
        ActivityLogService $activityLogService
    ) {
        $this->repository = $repository;
        $this->activityLogService = $activityLogService;
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
     $group = $this->repository->create($data);


        $actor = auth()->user();

        $this->activityLogService->logGroupCreated($group, $actor);

        return $group;
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
