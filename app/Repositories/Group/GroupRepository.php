<?php

namespace App\Repositories\Group;

use App\Models\Group;

class GroupRepository
{
    protected Group $model;


    public function __construct(Group $model)
    {
        $this->model = $model;
    }


    public function all()
    {
        return $this->model->all();
    }


    public function find(int $id)
    {
        return $this->model->findOrFail($id);
    }


    public function create(array $data)
    {
        return $this->model->create($data);
    }


    public function update(int $id, array $data)
    {
        $group = $this->find($id);
        $group->update($data);
        return $group;
    }


    public function delete(int $id)
    {
        return $this->model->destroy($id);
    }


    public function getStudents(int $groupId)
    {
        $group = $this->find($groupId);
        return $group->students;
    }

    public function getGroupsByUserId(int $userId)
    {
        return $this->model->where('user_id', $userId)->get();
    }


public function getGroupsByStudentId(int $studentId)
{
    return $this->model->whereHas('students', function($q) use ($studentId) {
        $q->where('user_id', $studentId);
    })->with(['announcements.user'])->get(); // duyuruları ve yazarları eager load ediyoruz
}


}
