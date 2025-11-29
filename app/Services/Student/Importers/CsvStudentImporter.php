<?php

namespace App\Services\Student\Importers;

use App\Services\Student\StudentService;
use Illuminate\Support\Facades\Log;

class CsvStudentImporter implements StudentImporterInterface
{
    protected StudentService $service;

    public function __construct(StudentService $service)
    {
        $this->service = $service;
    }

    public function import(array $data, int $groupId): array
    {
        $file = $data['file'];
        $students = [];
        $errors = [];

        if (($handle = fopen($file->getRealPath(), 'r')) !== false) {
            // BOM kontrolü
            $bom = fread($handle, 3);
            if ($bom != "\xEF\xBB\xBF") {
                rewind($handle);
            }

            // Headers
            $headers = fgetcsv($handle);

            // Başlıkları temizle
            $headers = array_map(function($header) {
                return strtolower(trim($header));
            }, $headers ?? []);

            $lineNumber = 1;

            while (($row = fgetcsv($handle)) !== false) {
                $lineNumber++;

                if (empty(array_filter($row))) continue;

                if (count($row) !== count($headers)) {
                    $errors[] = "Satır $lineNumber: Sütun sayısı uyuşmuyor.";
                    continue;
                }

                $rowData = array_combine($headers, $row);

                if (empty($rowData['name']) || empty($rowData['no'])) {
                    $errors[] = "Satır $lineNumber: Eksik veri (name veya no).";
                    continue;
                }

                try {
                    $name = trim($rowData['name']);
                    $no   = trim($rowData['no']);



                    $student = $this->service->createAndAttachToGroup([
                        'name'     => $name,
                        'no'       => $no,
                        'email'    => $no .'@dgn.com',
                        'password' => $name .'passworddgn',
                    ], $groupId);

                    $students[] = $student;

                } catch (\Exception $e) {
                    $errors[] = "Satır $lineNumber hatası: " . $e->getMessage();
                    Log::error($e->getMessage());
                }
            }
            fclose($handle);
        }

        if (empty($students) && !empty($errors)) {
            throw new \Exception('Hatalar oluştu: ' . implode('; ', array_slice($errors, 0, 3)));
        }

        return $students;
    }
}
