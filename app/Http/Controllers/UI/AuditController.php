<?php

namespace App\Http\Controllers\UI;

use App\Exports\ExportAnalytics;
use App\Helpers\UtilActivityLogging;
use App\Http\Controllers\Controller;
use App\Traits\DB\StoreTable;
use App\Traits\DB\TransactionTable;
use App\Traits\DB\UserActivityTable;
use App\Traits\DB\VendingMachineTable;
use Carbon\Carbon;
use DateTime;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use Spatie\Activitylog\Models\Activity;

class AuditController extends Controller
{
    use UserActivityTable;

    public function index()
    {
        $activities = $this->getActivities();

        return view('pages.audit.index',compact('activities'));
    }


}
