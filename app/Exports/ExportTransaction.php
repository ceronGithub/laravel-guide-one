<?php

namespace App\Exports;

use App\Traits\DB\TransactionTable;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class ExportTransaction implements FromCollection, WithHeadings
{
    use TransactionTable;

    private $id;

    public function __construct($id)
    {
        $this->id = $id;
    }

    public function headings(): array
    {
        return [
            'ID',
            'Purchase Order ID',
            'Payment Details ID',
            'Transaction ID',
            'Product Name',
            'Product Price',
            'Machine ID',
            'Machine Slot ID',
            'Transaction Type',
            'Transaction Description',
            'Request Date Time Expiry',
            'Created At',
            'Updated At',
            'ID',
            'Payment ID',
            'Terminal Message Status',
            'Terminal Merchant',
            'Terminal Date',
            'Terminal Time',
            'Terminal Paid Amount',
            'Terminal APPR Code',
            'Terminal Trace No',
            'Terminal TID',
            'Terminal MID',
            'Terminal Payment Mode',
            'Terminal Payment Mode Value',
            'Terminal Payment Mode Date',
            'Terminal Batch Number',
            'Terminal Reference Number',
            'Terminal Created At',
            'Terminal Updated At',
        ];
    }

    /**
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        return collect($this->getExportSinglePurchaseOrderData($this->id));
    }
}
