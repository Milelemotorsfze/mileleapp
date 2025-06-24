<?php

namespace App\Exports;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class SoTraceSummaryExport implements FromCollection, WithHeadings
{
    protected $summary;

    public function __construct(array $summary)
    {
        $this->summary = $summary;
    }

    public function collection()
    {
        return collect($this->summary)->map(function ($count, $table) {
            return [
                'Table' => $table,
                'Rows Linked' => $count,
            ];
        });
    }

    public function headings(): array
    {
        return ['Table', 'Rows Linked'];
    }
}
