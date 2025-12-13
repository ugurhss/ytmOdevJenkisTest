<?php

namespace App\Services\Student\Importers;

use App\Services\Student\StudentService;
use Maatwebsite\Excel\Facades\Excel;

class ExcelStudentImporter implements StudentImporterInterface
{
    protected StudentService $service;

    public function __construct(StudentService $service)
    {
        $this->service = $service;
    }

    public function import(array $data, int $groupId): array
    {
        $file = $data['file'];

        $rows = Excel::toArray([], $file)[0];

        $students = [];

        foreach ($rows as $index => $row) {
            if ($index === 0) continue;

            if (empty($row[0]) || empty($row[1])) continue;

            $name = trim($row[0]);
            $no   = trim($row[1]);

            $email = $no . '@dgn.com';
            $password = $name.'passworddgn';

            try {
                $students[] = $this->service->createAndAttachToGroup([
                    'name'     => $name,
                    'no'       => $no,
                    'email'    => $email,
                    'password' => $password,
                ], $groupId);
            } catch (\Exception $e) {

                continue;
            }
        }

        return $students;
    }
}
