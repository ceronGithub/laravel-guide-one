<?php

namespace App\Exports;

use App\Traits\DB\TransactionTable;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class ExportReport implements FromCollection, WithHeadings, WithMapping
{
    use TransactionTable;

    private $transactions;
    private $row = 0;

    public function __construct($transactions)
    {
        $this->transactions = $transactions;
    }

    public function headings(): array
    {
        return [
            '#',
            'Order Receipt',
            'Acknowledgement Receipt',
            'Terminal TID',
            'Vendo Name',
            'Serial Number',
            'Product Name',
            'Amount Paid',
            'Transaction Status',
            'Payment Method',
            'Date Paid'
        ];
    }

    public function map($data): array
    {
        $this->row++;
        return [
            $this->row,
            $data->purchase_order_id,
            $data->payment_details_id ?? '-',
            $data->payment_details->terminal_tid ?? '-',
            $data->machine->name ?? $data->machine_address_id,
            '-',
            $data->product_name,
            $data->product_price,
            $data->transaction_description ?? '-',
            $data->payment_details->terminal_payment_mode ?? '-' ,
            $data->payment_details->created_at ?? '-',
        ];
    }

    /**
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        return $this->transactions;
    }
}
