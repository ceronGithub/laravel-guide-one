<?php

namespace App\Traits\DB;

use App\Exports\ExportTransaction;
use App\Models\Machine;
use App\Models\PaymentDetail;
use App\Models\Transaction;
use App\Requests\ParameterBuilder\Report\FilterBuilder;
use App\Requests\Transaction\PaymentRequest;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Facades\Excel;

trait TransactionTable
{

    use MachineTable;

    public function getPurchaseOrderData(String $poId, bool $withPaymentDetails = false)
    {
        if ($withPaymentDetails) {
            $data = Transaction::with(Transaction::OBJECT_PAYMENT_DETAILS)
                ->where(Transaction::COLUMN_PURCHASE_ORDER_ID, $poId)->firstOrFail();
        } else {
            $data = Transaction::where(Transaction::COLUMN_PURCHASE_ORDER_ID, $poId)->firstOrFail();
        }
        return $data;
    }

    public function getTransactionList(array $vendoMachineList = null, bool $withPayment = false)
    {
        if ($withPayment) {
            $data = Transaction::with(Transaction::OBJECT_PAYMENT_DETAILS)->whereNotNull(Transaction::COLUMN_PAYMENT_DETAILS_ID)->get();
        } else {
            $data = Transaction::with(Transaction::OBJECT_PAYMENT_DETAILS)->get();
        }
        return $data;
    }

    public function getTransactionListWithPaginate(FilterBuilder $filter, int $paginateCount = 10, bool $sortByLatest = false, bool $withMachine = false)
    {
        $query = $this->generateReportQuery($filter, $sortByLatest, $withMachine);

        return $query->paginate($paginateCount);
    }

    public function generateReport(FilterBuilder $filter, bool $sortByLatest = false, bool $withMachine = false)
    {
        $query = $this->generateReportQuery($filter, $sortByLatest, $withMachine);

        return $query->get();
    }

    private function generateReportQuery(FilterBuilder $filter, bool $sortByLatest = false, bool $withMachine = false)
    {
        $filterFrom = $filter->getFilterFrom() ?? Date('01-01-2020');
        $filterTo = Carbon::parse($filter->getFilterTo()) ?? Carbon::now()->addDay();
        $filterTo->addDay();
        $relationship = [];
        if ($withMachine) {
            $query = Transaction::with(Transaction::OBJECT_MACHINE);
            array_push($relationship, Transaction::OBJECT_MACHINE);
        }

        array_push($relationship, Transaction::OBJECT_PAYMENT_DETAILS);

        if ($filter->getwithPayment() && $filter->getTransactionType() != null && $filter->getTransactionType() != 1) {
            $query = Transaction::with($relationship)
                ->wherehas(Transaction::OBJECT_PAYMENT_DETAILS, function ($query) use ($filter) {
                    if ($filter->getPaymentMode() != null) {
                        $query = $query->where((Transaction::OBJECT_PAYMENT_DETAILS . "." . PaymentDetail::COLUMN_TERMINAL_PAYMENT_MODE),
                            $filter->getPaymentMode()
                        );
                    }
                });
        } else {
            $query = Transaction::with($relationship);
        }

        if (count($filter->getMachineAddressId())) {
            $query = $query->whereIn(Transaction::COLUMN_MACHINE_ADDRESS_ID, $filter->getMachineAddressId());
        }

        if ($filter->getTransactionType() != null) {
            $query = $query->where(Transaction::COLUMN_TRANSACTION_TYPE, $filter->getTransactionType());
        }

        $query = $query->whereBetween("transactions.created_at", [$filterFrom, $filterTo])
            ->orderBy("id", ($sortByLatest) ? 'desc' : 'asc');

        return $query;
    }

    public function getFirstItemSerial($serial): String
    {
        $array = array_map('trim', explode(',', $serial));

        $firstItem = isset($array[0]) ? $array[0] : null;

        return $firstItem;
    }

    public function generateAnalytics(
        array $machineAddressId = [],
        string $filterFrom = null,
        string $order = null,
        string $sort = null
    ) {
        $relationship = [];

        array_push($relationship, Machine::OBJECT_TRANSACTIONS);

        $query = Machine::with($relationship, function ($query) use ($filterFrom) {
            $query->with(Transaction::OBJECT_PAYMENT_DETAILS);

            if ($filterFrom != null) {
                $query = $query->whereDate(Transaction::CREATED_AT,  '=', $filterFrom);
            }
        })->withCount([
            Machine::OBJECT_TRANSACTIONS . ' AS total_sale' => function ($query) use ($filterFrom) {
                $query->select(DB::raw('SUM(product_price)'));
            }
        ])->join('stores', 'machines.' . Machine::COLUMN_STORE_ID, '=', 'stores.' . 'id');

        $query->whereIn(Transaction::COLUMN_MACHINE_ADDRESS_ID, $machineAddressId)
            ->groupBy(Transaction::COLUMN_MACHINE_ADDRESS_ID);

        switch ($sort) {
            case "vending":
                $sort = "name";
                break;
            case "stores":
                $sort = "stores.name";
                break;
            case "sales":
                $sort = "total_sale";
                break;
            case "time":
                $sort = "peak_hrs";
                break;
            default:
                $sort = "";
                break;
        }
        if (($order != "asc" || $order != "desc") && $sort != "") {
            $query->orderBy($sort, $order);
        }

        return $query->select(['machines.*', DB::raw($this->generatePeakTimeQuery($filterFrom)), DB::raw($this->generateTotalSales($filterFrom))])->get();
    }

    public function generateTotalSales($filterFrom = null)
    {
        $query = '(
            SELECT
                SUM(transactions.product_price)
            FROM
                transactions
            INNER JOIN
                payment_details
            ON
                payment_details.payment_id = transactions.payment_details_id
            WHERE
                machines.machine_address_id = transactions.machine_address_id';

        if ($filterFrom != null) {
            $query .= ' AND payment_details.created_at >= \'' . Carbon::parse($filterFrom)->toDateString() . '\' AND payment_details.created_at < \'' . Carbon::parse($filterFrom)->addDay(1)->toDateString() . '\'';
        }

        return $query .= ') AS total_sale';
    }

    public function generatePeakTimeQuery($filterFrom  = null)
    {
        $query = '(
            SELECT
                DATE_FORMAT(payment_details.created_at, "%H:00")
            FROM
                transactions
            INNER JOIN
                payment_details
            ON
                payment_details.payment_id = transactions.payment_details_id
            WHERE
                machines.machine_address_id = transactions.machine_address_id';

        if ($filterFrom != null) {
            $query .= ' AND payment_details.created_at >= \'' . Carbon::parse($filterFrom)->toDateString() . '\' AND payment_details.created_at < \'' . Carbon::parse($filterFrom)->addDay(1)->toDateString() . '\'';
        }

        $query .= ' GROUP BY
                transactions.machine_address_id, DATE_FORMAT(payment_details.created_at, "%H:00")
            ORDER BY
                COUNT(transactions.id) DESC
            LIMIT
                1
            ) AS peak_hrs';

        return $query;
    }

    public function generatePeakTime(array $machineAddressIds = [], string $filterFrom = null): array
    {
        $peakHrs = [];
        foreach ($machineAddressIds as $id) {
            $select =
                'SELECT
                    machine_address_id,
                    payment_details.created_at,
                    COUNT(transactions.id) AS transaction_count,
                    DATE_FORMAT(payment_details.created_at, "%H:00") AS peak_hr
                FROM
                    transactions
                INNER JOIN
                    payment_details
                ON
                    payment_details_id = payment_id
                WHERE
                    machine_address_id = \'' . $id . '\'';


            if ($filterFrom != null) {
                $select .= '
                    AND
                        DATE(payment_details.created_at) = \'' . $filterFrom . '\'';
            }

            $select .= '
                GROUP BY
                    machine_address_id,
                    DATE_FORMAT(payment_details.created_at, "%H:00")
			    ORDER BY
                    transaction_count DESC
			    LIMIT 1;';

            $peakHr = DB::select($select);

            if (count($peakHr) > 0)
                $peakHrs[$id] = $peakHr[0]->peak_hr;
        }

        return $peakHrs;
    }

    public function convertMachineListQueryToMachineAddressIds($machineQuery)
    {
        $machineAddIds = null;
        for ($index = 0; $index < count($machineQuery); $index++) {
            $machineAddIds[$index] = $machineQuery[$index]->machine_address_id;
        }
        return $machineAddIds;
    }

    public function getExportSinglePurchaseOrderData($poId)
    {
        $buildSelect = $this->buildSelect(Transaction::TBL_NAME, Transaction::TBL_COLUMN_NAMES);
        $buildSelect .= "," . $this->buildSelect(PaymentDetail::TBL_NAME, PaymentDetail::TBL_COLUMN_NAMES);

        $query = "select " . $buildSelect . "  from `" . Transaction::TBL_NAME . "` " .
            "LEFT JOIN `" . PaymentDetail::TBL_NAME . "` ON `" . Transaction::TBL_NAME . "`.`" . Transaction::COLUMN_PAYMENT_DETAILS_ID . "`=`" . PaymentDetail::TBL_NAME . "`.`" . PaymentDetail::COLUMN_PAYMENT_ID . "` " .
            "WHERE `" . Transaction::TBL_NAME . "`.`" . Transaction::COLUMN_PURCHASE_ORDER_ID . "` = '" . $poId . "'";

        return DB::select($query);
    }

    public function exportSinglePurchaseOrderData($purchaseOrderId)
    {
        $time = Carbon::now();
        Excel::store(new ExportTransaction($purchaseOrderId), 'public/generated/OR/' . $time->format('Y') . '/' .  $time->format('m') . '/' . $time->format('d') . '/' . $purchaseOrderId . '.csv', 'local');
    }

    public function insertPurchaseOrder(array $array)
    {
        $data = Transaction::create($array);
        return $data;
    }

    public function generateTransactionId($poId)
    {
        $data = $this->getPurchaseOrderData($poId, true);
        $array = $data->toArray();

        unset($array[Transaction::COLUMN_TRANSACTION_ID]);
        // Remove Updated at for consistency
        unset($array['updated_at']);

        $data->transaction_id = hash_hmac('sha256', json_encode($array), $array[Machine::COLUMN_MACHINE_ADDRESS_ID]);
        $data->update();
        return $data;
    }

    public function setAsRequestOrder(array $array): array
    {
        $array = array_merge($array, [
            Transaction::COLUMN_TRANSACTION_TYPE => 1,
            Transaction::COLUMN_TRANSACTION_DESCRIPTION => 'Request Purchase Order'
        ]);
        return $array;
    }

    public function updateTransactionType($data, $request)
    {
        switch ($request->terminal_message_status) {
            case "APPROVED":
            case "SUCCESS":
            case "Transaction Successful":
            case "Transaction Successful with 3DS":
                $this->setAsPaymentCollected($data);
                break;
            default:
                $this->setAsPaymentFailed($data);
                break;
        }
        $data->update();

        return $data;
    }

    public function setAsPaymentCollected($data)
    {
        $data->transaction_type = 2;
        $data->transaction_description = 'Payment Collected';
    }

    public function setAsPaymentFailed($data)
    {
        $data->transaction_type = 3;
        $data->transaction_description = 'Payment Failed';
    }

    public function addExpiryDateTime(array $array, $minutes = 10): array
    {
        $currentDateTime = Carbon::now()
            ->addMinutes($minutes)
            ->format('Y-m-d H:i:m');
        $array = array_merge($array, [
            Transaction::COLUMN_REQUEST_DATETIME_EXPIRY => $currentDateTime,
        ]);
        return $array;
    }

    public function addSerial(array $array, String $serial): array
    {
        $array = array_merge($array, [
            Transaction::COLUMN_PRODUCT_SERIAL => $serial,
        ]);
        return $array;
    }

    public function addPurchaseOrder(array $array): array
    {
        $array = array_merge($array, [
            Transaction::COLUMN_PURCHASE_ORDER_ID => "O" . mt_rand(100000000, 999999999),
        ]);
        return $array;
    }

    public function addProductData(array $array, $productdata): array
    {
        $array = array_merge($array, [
            Transaction::COLUMN_PRODUCT_NAME => $productdata->name,
            Transaction::COLUMN_AMOUNT => $productdata->price,
        ]);
        return $array;
    }

    public function sendTransactionToFtp()
    {
        $localFile = Storage::disk('local')->get('/public/generated/testing_globe_vendo.txt');

        Storage::disk('globe-ftp')->put('/var/www/html/staging/globe-vendo-system/storage/testing_globe_vendo4.txt', $localFile);
    }
}
