<?php

namespace App\Traits\DB;

use App\Models\Category;
use App\Models\Machine;
use App\Models\MachineSlot;
use App\Models\Product;
use App\Models\Transaction;
use App\Models\User;
use App\Models\UserActivity;
use App\Requests\Machine\RegisterMachineRequest;
use App\Requests\Machine\RegisterMachineSlotRequest;
use App\Requests\Machine\ResetSlotRequest;
use Illuminate\Support\Facades\DB;
use Spatie\Activitylog\Models\Activity;

trait UserActivityTable
{

    public function recordRegisterMachineSlot($userData, $machineId)
    {
        $record = UserActivity::create([
            UserActivity::COLUMN_USER_ID => $userData->id,
            UserActivity::COLUMN_DESC => "User " . $userData->first_name . " " . $userData->last_name . " added new vending machine with ID " . $machineId
        ]);
        return $record;
    }

    public function recordRegisterMachineSlotId($userData, $machineAddressId, $productId, $machineId)
    {
        $record = UserActivity::create([
            UserActivity::COLUMN_USER_ID => $userData->id,
            UserActivity::COLUMN_DESC => "User " . $userData->first_name . " " . $userData->last_name .
                " added new machine slot with ID " . $machineAddressId .
                " holding product id " . $productId .
                " in vending machine " . $machineId
        ]);
        return $record;
    }

    public function getActivities()
    {
        $activities = Activity::orderBy("created_at", 'desc')->paginate(30);
        return $activities;
    }
}
