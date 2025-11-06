<?php

namespace App\Services\Group;

use App\Repositories\Group\GroupRepository;

class GroupService
{
    protected GroupRepository $repository;

    /**
     * Constructor
     * GroupRepository servise enjekte edilir.
     */
    public function __construct(GroupRepository $repository)
    {
        $this->repository = $repository;
    }

    /**
     * Tüm grupları getirir.
     */
    public function getAll()
    {

        return $this->repository->all();
    }

    /**
     * ID’ye göre grup getirir.
     */
    public function getById(int $id)
    {
        return $this->repository->find($id);
    }

    /**
     * Yeni grup oluşturur.
     */
    public function create(array $data)
    {
        return $this->repository->create($data);
    }

    /**
     * Grup günceller.
     */
    public function update(int $id, array $data)
    {
        return $this->repository->update($id, $data);
    }

    /**
     * Grubu siler.
     */
    public function delete(int $id)
    {
        return $this->repository->delete($id);
    }

    /**
     * Grubun öğrencilerini getirir.
     */
    public function getStudents(int $groupId)
    {
        return $this->repository->getStudents($groupId);
    }

    /**
     * Bir kullanıcının oluşturduğu grupları getirir.
     */
    public function getGroupsByUser(int $userId)
    {
        return $this->repository->getGroupsByUserId($userId);
    }
}
