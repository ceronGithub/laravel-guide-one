<?php

namespace App\Http\Controllers\UI;

use App\Helpers\UtilActivityLogging;
use App\Http\Controllers\Controller;
use App\Requests\ParameterBuilder\Report\FilterBuilder;
use App\Traits\DB\TransactionTable;

class TransactionController extends Controller
{
    use TransactionTable;

    public function index()
    {
        $transactions = $this->getTransactionListWithPaginate(new FilterBuilder(), 30, true);

        UtilActivityLogging::saveUserActivityLog("User accessed the list of transactions", null);

        return view('pages.transactions.index', compact('transactions'));
    }

}
