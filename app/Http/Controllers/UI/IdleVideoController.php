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
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Facades\Excel;
use Spatie\Activitylog\Models\Activity;

class IdleVideoController extends Controller
{

    public function index()
    {
        return view('pages.idlevideo.index');
    }

    public function upload(Request $request)
    {
        $image = $request->file('video');

        $imageName = 'temp.mp4';
        $image->move(public_path('storage/video/idle'), $imageName);
    }

    public function publish(Request $request)
    {
        $sourceFilePath = public_path('storage/video/idle/temp.mp4');
        $destinationPath = public_path('storage/video/idle') . '/idle-video.mp4'; // Change this to your desired destination

        if (!File::exists($sourceFilePath)) {
            Session::flash('success', "Failed to update Welcome Screen Video");
            return redirect()->route('idle-video.index');
        }

        if (!File::isFile($sourceFilePath)) {
            Session::flash('success', "Failed to update Welcome Screen Video");
            return redirect()->route('idle-video.index');
        }

        File::move($sourceFilePath, $destinationPath);

        Session::flash('success', "Successfully updated Welcome Screen Video");
        return redirect()->route('idle-video.index');
    }
}
