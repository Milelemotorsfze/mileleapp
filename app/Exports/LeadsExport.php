<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class LeadsExport implements FromCollection, WithHeadings, WithColumnFormatting, WithStyles
{
    protected $data;
    protected $headings;

    public function __construct(array $data, array $headings)
    {
        $this->data = $data;
        $this->headings = $headings;
    }

    public function collection()
    {
        return collect($this->data);
    }

    public function headings(): array
    {
        // Use the headings provided during instantiation
        return $this->headings;
    }

    public function columnFormats(): array
    {
        return [
            'B' => NumberFormat::FORMAT_TEXT, // Phone column (2nd column)
        ];
    }

    public function styles(Worksheet $sheet)
    {
        // Set phone column (B) to text format to prevent scientific notation
        $sheet->getStyle('B:B')->getNumberFormat()->setFormatCode(NumberFormat::FORMAT_TEXT);
        
        // Set column widths for better readability
        $sheet->getColumnDimension('A')->setWidth(20); // Name
        $sheet->getColumnDimension('B')->setWidth(15); // Phone
        $sheet->getColumnDimension('C')->setWidth(25); // Email
        $sheet->getColumnDimension('D')->setWidth(15); // Location
        $sheet->getColumnDimension('E')->setWidth(10); // Language
        
        return [];
    }
}
