<?php

namespace App\Exports;

use App\Traits\DB\TransactionTable;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class ExportAnalytics implements FromCollection, WithHeadings, WithMapping
{
    use TransactionTable;

    private $analytics;
    private $row = 0;

    public function __construct($analytics)
    {
        $this->analytics = $analytics;
    }

    public function headings(): array
    {
        return [
            '#',
            'Vending Machine',
            'Store Name',
            'Total Sales',
            'Peak Time',
        ];
    }

    public function map($data): array
    {
        $this->row++;
        return [
            $this->row,
            $data->name,
            $data->store->name,
            $data->total_sale ?? "0",
            $data->peak_hrs ?? "-"
        ];
    }

    /**
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        return $this->analytics;
    }
}
