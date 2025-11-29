<?php

namespace App\Services\Student\Importers;

interface StudentImporterInterface
{
    public function import(array $data, int $groupId): array;
}
