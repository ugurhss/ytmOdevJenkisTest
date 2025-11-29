<?php

namespace App\Services\Student\Importers;

use App\Services\Student\StudentService;

class ManualStudentImporter implements StudentImporterInterface
{
    protected StudentService $service;

    public function __construct(StudentService $service)
    {
        $this->service = $service;
    }

    public function import(array $data, int $groupId): array
    {
        // Otomatik veri oluşturma
        // Email: no@dgn.com
        // Şifre: namepassworddgn

        $preparedData = [
            'name'     => $data['name'],
            'no'       => $data['no'],
            'email'    => $data['no'] . '@dgn.com',
            'password' => $data['name'].'passworddgn',
        ];

        $student = $this->service->createAndAttachToGroup($preparedData, $groupId);

        return [$student];
    }
}
