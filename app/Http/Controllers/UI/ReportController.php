<?php

namespace App\Http\Controllers\UI;

use App\Exports\ExportReport;
use App\Helpers\UtilActivityLogging;
use App\Http\Controllers\Controller;
use App\Requests\ParameterBuilder\Report\FilterBuilder;
use App\Requests\Report\ReportRequest;
use App\Traits\DB\StoreTable;
use App\Traits\DB\TransactionTable;
use App\Traits\DB\VendingMachineTable;
use DateTime;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class ReportController extends Controller
{
    use TransactionTable, StoreTable, VendingMachineTable;

    public function index(Request $request)
    {
        $from = null;
        $to = null;

        if ($this->validateDate($request->input("filter-from")) && $this->validateDate($request->input("filter-to"))) {
            $from = $request->input("filter-from");
            $to = $request->input("filter-to");
        }

        if (auth()->user()->role_id != 1) {
            $stores = $this->getStoresViaUserId(auth()->user()->id);
            $machineList = $this->getMachinesViaStores($stores);
        } else {
            $machineList = $this->getAllMachineList();
        }

        $paymentMode = $request->input("filter-payment-mode");
        $transactionModeId = $request->input("filter-tx-type");

        $currentMachineAddressId = $request->input("filter-machine-add-id");

        if ($currentMachineAddressId != null)
            $machineAddressIds = [$request->input("filter-machine-add-id")];
        else
            $machineAddressIds = $this->convertMachineListQueryToMachineAddressIds($machineList);

        $transactions = $this->getTransactionListWithPaginate(
            new FilterBuilder(true, $from, $to, $machineAddressIds, $paymentMode, $transactionModeId),
            15,
            true,
            true
        );
                
        UtilActivityLogging::saveUserActivityLog("User accessed the reports", null);

        return view('pages.reports.index', compact('transactions', 'from', 'to', 'paymentMode', 'transactionModeId', 'machineList'));
    }

    public function export(Request $request)
    {
        $from = null;
        $to = null;

        if ($this->validateDate($request->input("filter-from")) && $this->validateDate($request->input("filter-to"))) {
            $from = $request->input("filter-from");
            $to = $request->input("filter-to");
        }

        if (auth()->user()->role_id != 1) {
            $stores = $this->getStoresViaUserId(auth()->user()->id);
            $machineList = $this->getMachinesViaStores($stores);
        } else {
            $machineList = $this->getAllMachineList();
        }

        $paymentMode = $request->input("filter-payment-mode");
        $transactionModeId = $request->input("filter-tx-type");

        $currentMachineAddressId = $request->input("filter-machine-add-id");

        if ($currentMachineAddressId != null)
            $machineAddressIds = [$request->input("filter-machine-add-id")];
        else
            $machineAddressIds = $this->convertMachineListQueryToMachineAddressIds($machineList);

        $transactions = $this->generateReport(
            new FilterBuilder(true, $from, $to, $machineAddressIds, $paymentMode, $transactionModeId),
            true,
            true
        );

        UtilActivityLogging::saveUserActivityLog("User exported a report.", null);

        return Excel::download(new ExportReport($transactions), 'report.xlsx');
    }

    function validateDate($date, $format = 'Y-m-d')
    {
        $d = DateTime::createFromFormat($format, $date);
        return $d && $d->format($format) === $date;
    }
}
