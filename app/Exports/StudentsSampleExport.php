<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class StudentsSampleExport implements FromArray, WithHeadings, WithStyles
{
    public function array(): array
    {
        return [
            ['Ahmet Yılmaz', '101'],
            ['Ayşe Demir', '102'],
            ['Mehmet Kaya', '103'],
        ];
    }

    public function headings(): array
    {
        return [
            'name',
            'no'
        ];
    }

    public function styles(Worksheet $sheet)
    {
        return [
            1 => ['font' => ['bold' => true]],
        ];
    }
}
