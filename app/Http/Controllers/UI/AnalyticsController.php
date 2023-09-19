<?php

namespace App\Http\Controllers\UI;

use App\Exports\ExportAnalytics;
use App\Helpers\UtilActivityLogging;
use App\Http\Controllers\Controller;
use App\Traits\DB\StoreTable;
use App\Traits\DB\TransactionTable;
use App\Traits\DB\VendingMachineTable;
use Carbon\Carbon;
use DateTime;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class AnalyticsController extends Controller
{
    use TransactionTable, StoreTable, VendingMachineTable;

    public function index(Request $request)
    {
        $from = null;

        if ($this->validateDate($request->input("filter-from"))) {
            $from = $request->input("filter-from");
        }

        if (auth()->user()->role_id != 1) {
            $stores = $this->getStoresViaUserId(auth()->user()->id);
            $machineList = $this->getMachinesViaStores($stores);
        } else {
            $machineList = $this->getAllMachineList();
        }
        $machineAddressIds = $this->convertMachineListQueryToMachineAddressIds($machineList);
        $analytics = $this->generateAnalytics($machineAddressIds, $from, $request->get("order"), $request->get("sort"));

        UtilActivityLogging::saveUserActivityLog("User accessed the analytics", null);

        return view('pages.analytics.index', compact('analytics'));
    }


    public function export(Request $request)
    {
        $from = null;

        if ($this->validateDate($request->input("filter-from"))) {
            $from = $request->input("filter-from");
        }

        if (auth()->user()->role_id != 1) {
            $stores = $this->getStoresViaUserId(auth()->user()->id);
            $machineList = $this->getMachinesViaStores($stores);
        } else {
            $machineList = $this->getAllMachineList();
        }
        $machineAddressIds = $this->convertMachineListQueryToMachineAddressIds($machineList);
        $analytics = $this->generateAnalytics($machineAddressIds, $from, $request->get("order"), $request->get("sort"));

        UtilActivityLogging::saveUserActivityLog("User exported analytics report.", null);

        return Excel::download(new ExportAnalytics($analytics), 'analytics.xlsx');
    }

    function validateDate($date, $format = 'Y-m-d')
    {
        $d = DateTime::createFromFormat($format, $date);
        return $d && $d->format($format) === $date;
    }
}
