<?php
namespace App\Traits\Utilities;

use App\Models\Transaction;

trait LukeFileGenerator
{

    public function generateFile(string $purchaseOrderNo) {
        try{

            $directory   = $this->createDirectory();
            $content     = $this->composeContent($purchaseOrderNo);
            $this->updateFile($directory . 'VMMS'.date('Ymd').'.txt', $content);
            return true;
        }catch(\Exception $e){
            return false;
        }

        return false;
    }

    public function composeContent(string $purchaseOrderNo){
        $transaction = Transaction::where('purchase_order_id', $purchaseOrderNo)->first();
        $data = [
            'TranDate' => [
                'data'   => date('Ymd', strtotime($transaction->created_at)),
                'length' => 8
            ],
            'Customer Name' => [
                'data'   => '',
                'length' => 40
            ],
            'Customer Account No' => [
                'data'   => '',
                'length' => 20
            ],
            'Tran Amount' => [
                'data'   => intval($transaction->product_price),
                'length' => 13,
                'pad'    => 'left'
            ],
            'DepositAcct No' => [
                'data'   => '',
                'length' => 15
            ],
            'Service No' => [
                'data'   => '',
                'length' => 20,
            ],
            'Bank Code' => [
                'data'   => '',
                'length' => 10
            ],
            'Bank Acct-Check No' => [
                'data'   => '',
                'length' => 20
            ],
            'Terminal No' => [
                'data'   => '',
                'length' => 14
            ],
            'Receipting Ref No' => [
                'data'   => $transaction->purchase_order_id,
                'length' => 24
            ],
            'Debit/Credit Indicator(187):C-Credit(+) D-Debit(-)' => [
                'data'   => $transaction->payment_details->terminal_payment_mode == 'credit_card' ? 'C' : '',
                'length' => 1
            ],
            'Cash/Check Indicator(188):0-Cash 1-Check' => [
                'data'   => $transaction->payment_details->terminal_payment_mode == 'gcash' ? '0' : '',
                'length' => 1
            ],
            'Payment Mode(189-190):01-for Acctg confirmation' => [
                'data'   => '',
                'length' => 2
            ],
            'Application Date (191-198)' => [
                'data'   => date('Ymd', strtotime($transaction->created_at)),
                'length' => 8
            ],
            'Credit Card Approval No.(199-213)' => [
                'data'   => $transaction->payment_details->terminal_appr_code,
                'length' => 15
            ],
            'Credit Card Expiration Dt(214-221)' => [
                'data'   => '',
                'length' => 8
            ],
            'Filler (222-231, char(10))' => [
                'data'   => '',
                'length' => 10
            ],
            'Distribution Channel (232-233, char(2))' => [
                'data'   => '',
                'length' => 2
            ],
            'Payment Type(234-245, char(12))' => [
                'data'   => strtoupper($transaction->payment_details->terminal_payment_mode),
                'length' => 12
            ],
            'Pay Type Detail 1 (246-265, char(20))' => [
                'data'   => $transaction->product_code,
                'length' => 20
            ],
            'Pay Type Detail 2 (266-285, char(20))' => [
                'data'   => $transaction->store_code,
                'length' => 20
            ],
            'Pay Type Detail 3 (286-305, char(20))' => [
                'data'   => $transaction->product_serial,
                'length' => 20
            ],
            'Qty (306-310, numeric/char(5))' => [
                'data'   => 1,
                'length' => 5
            ],
            'Other Info(311-330 char(20))' => [
                'data'   => '',
                'length' => 20
            ],
            'Charge Slip No(331-350 char(20))' => [
                'data'   => '',
                'length' => 20
            ],
            'Service Type ( 351-365 char(15))' => [
                'data'   => '',
                'length' => 15
            ],
            ' E-mail address' => [
                'data'   => '',
                'length' => 50
            ],
        ];

        $string = "";
        foreach($data as $key => $row){
            if(isset($row['pad']) && $row['pad'] == 'left'){
                $string .= str_pad($row['data'], $row['length'], "0", STR_PAD_LEFT);
            }else{
                $string .= str_pad($row['data'], $row['length'], " ");
            }
        }

        return $string . PHP_EOL;
    }

    public function updateFile(string $filePath, string $content){
        // Text to append to the file
        $textToAppend = $content;

        // Open the file in append mode (creates the file if it doesn't exist)
        $file = fopen($filePath, 'a');

        if ($file) {
            // Write the text to the file
            fwrite($file, $textToAppend);

            // Close the file
            fclose($file);

            return true;
        } else {
            return false;
        }
    }

    public function createDirectory(){
        // Get the current year, month, and date
        $currentYear  = date('Y');
        $currentMonth = date('m');
        $currentDate  = date('d');

        // Define the base directory where you want to create the folders
        $baseDirectory = public_path('storage/generated/OR/');

        // Create the year directory if it doesn't exist
        $yearDirectory = $baseDirectory . $currentYear . '/';
        if (!file_exists($yearDirectory)) {
            mkdir($yearDirectory);
        }

        // Create the month directory if it doesn't exist
        $monthDirectory = $yearDirectory . $currentMonth . '/';
        if (!file_exists($monthDirectory)) {
            mkdir($monthDirectory);
        }

        // Create the date directory if it doesn't exist
        $dateDirectory = $monthDirectory . $currentDate . '/';
        if (!file_exists($dateDirectory)) {
            mkdir($dateDirectory);
        }

        return $dateDirectory;
    }
}
