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

        // İlk sheet'i dizi olarak al
        $rows = Excel::toArray([], $file)[0];

        $students = [];

        foreach ($rows as $index => $row) {
            // Başlık satırını atla (varsayım: ilk satır başlık)
            if ($index === 0) continue;

            // Boş satır kontrolü
            if (empty($row[0]) || empty($row[1])) continue;

            $name = trim($row[0]); // A sütunu: name
            $no   = trim($row[1]); // B sütunu: no

            // Otomatik üretim
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
