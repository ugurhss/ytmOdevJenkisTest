<?php

namespace App\Services\Student\Importers;

use App\Services\Student\StudentService;

class StudentImporterFactory
{
    public static function make(string $type): StudentImporterInterface
    {
        $service = app(StudentService::class);

        return match ($type) {
            'manual' => new ManualStudentImporter($service),
            'csv' => new CsvStudentImporter($service),
            'excel' => new ExcelStudentImporter($service),
            default => throw new \Exception("Invalid import type: $type")
        };
    }
}
