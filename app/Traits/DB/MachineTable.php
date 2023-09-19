<?php

namespace App\Traits\DB;

use App\Models\Category;
use App\Models\Machine;
use App\Models\MachineSlot;
use App\Models\Product;
use App\Models\Transaction;
use App\Requests\Machine\RegisterMachineRequest;
use App\Requests\Machine\RegisterMachineSlotRequest;
use App\Requests\Machine\ResetSlotRequest;
use App\Requests\Machine\UpdateMachineSlotRequest;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

trait MachineTable
{
    use UserActivityTable;

    public function getMachineListWithPaginate(string $storeId)
    {
        return Machine::where(Machine::COLUMN_STORE_ID, $storeId)->paginate(10);
    }

    public function getMachineData(string $id)
    {
        return Machine::where(Machine::COLUMN_MACHINE_ADDRESS_ID, $id)
            ->first();
    }

    public function getSelectedMachineSlotDetail($id)
    {
        return MachineSlot::where(MachineSlot::COLUMN_MACHINE_ID, $id)
            ->first();
    }

    public function getMachineSlotDataByIndex($id)
    {
        return MachineSlot::where('id', $id)
            ->first();
    }

    public function getMachineByIndex($id)
    {
        return Machine::where('id', $id)
            ->first();
    }

    public function deleteMachineSlot($id)
    {
        $data = MachineSlot::where("id", $id)
            ->delete();
        return $data;
    }

    public function deleteMachine($id)
    {
        $data = Machine::where("id", $id)
            ->delete();
        return $data;
    }

    public function RegisterMachineSlot(array $request)
    {
        return MachineSlot::create($request);
    }

    public function getMachinesViaStores($stores)
    {
        $storeIds = null;
        for($index = 0; $index < count($stores); $index++){
            $storeIds[$index] = $stores[$index]->id;
        }
        return Machine::whereIn(Machine::COLUMN_STORE_ID, $storeIds)->get();
    }

    public function StoreID($store_id)
    {
        return Machine::where([Machine::COLUMN_MACHINE_ADDRESS_ID => $store_id])->first(['store_id'])->store_id;
    }

    public function UpdateMachineSlot($id, $machineProductID, $machineMaxCount, $machineCurrentCount, $stock, $serial, $spareqty)
    {
        $update = MachineSlot::where('id', $id)->first();
        $update->product_id = $machineProductID;
        $update->max_count = $machineMaxCount;
        $update->current_count =  $machineCurrentCount;
        $update->stock_alert = $stock;
        $update->serial =  $serial;
        $update->reserve_quantity_count =  $spareqty;
        $update->save();
        return $update;
    }

    public function UpdateMachineSlotSpareParts($id, $spareQty)
    {
        $update = MachineSlot::where('id', $id)->first();
        $update->reserve_quantity_count = $spareQty;
        $update->save();
        return $update;
    }    

    public function getMachineList(string $storeId)
    {
        return Machine::where(Machine::COLUMN_STORE_ID, $storeId)->get();
    }

    public function getAllMachineList()
    {
        return Machine::get();
    }

    public function checkMachineSlotExists(string $slot_id, string $machineSlotId)
    {
        return MachineSlot::where(MachineSlot::COLUMN_SLOT_ID, $slot_id)->where(MachineSlot::COLUMN_MACHINE_ID, $machineSlotId)->exists();
    }

    public function getMachineSlotData(string $id, string $categoryId = null, string $sort = null)
    {
        $query = MachineSlot::wherehas(MachineSlot::OBJECT_PRODUCT, function ($query) use ($categoryId) {
            // Check If filter by category ID
            if ($categoryId != null) {
                $query->where(Product::COLUMN_CATEGORY_ID, $categoryId);
            }
        })->with(
            [
                // MachineSlot::OBJECT_PRODUCT => fn ($query) => $query->with(Product::OBJECT_CATEGORY),
                // 'products.category',
                MachineSlot::OBJECT_PRODUCT . '.' . Product::OBJECT_CATEGORY,
                MachineSlot::OBJECT_MACHINE,
            ]
        );

        $query->join('products', 'products.id', '=', 'machine_slots.product_id');

        // Check Machine ID
        $query->where(MachineSlot::COLUMN_MACHINE_ID, $id);

        // Check if sorted by latest
        switch ($sort) {
            case "1":
                $query->latest();
                break;

            case "2":
                $query->orderBy("price");
                break;

            case "3":
                $query->orderBy("price", 'desc');
                break;
        }

        return $query->get();
    }

    public function getMachineSlotData1(string $id, string $categoryId = null, string $sort = null)
    {
        $buildSelect = $this->buildSelect(MachineSlot::TBL_NAME, MachineSlot::TBL_COLUMN_NAMES);
        $buildSelect .= "," . $this->buildSelect(Product::TBL_NAME, Product::TBL_COLUMN_NAMES);
        $buildSelect .= "," . $this->buildSelect(Category::TBL_NAME, Category::TBL_COLUMN_NAMES);
        $buildSelect .= "," . $this->buildSelect(Machine::TBL_NAME, Machine::TBL_COLUMN_NAMES);

        // Add Count
        $buildSelect .= ", COUNT(`" . Transaction::TBL_NAME . "`.`id`) AS transaction_count";

        $query = "select " . $buildSelect . " from `" . MachineSlot::TBL_NAME . "` " .
            "INNER JOIN `" . Product::TBL_NAME . "` ON `" . Product::TBL_NAME . "`.`id`=`" . MachineSlot::TBL_NAME . "`.`product_id` " .
            "INNER JOIN `" . Category::TBL_NAME . "` ON `" . Category::TBL_NAME . "`.`id`=`" . Product::TBL_NAME . "`.`category_id` " .
            "INNER JOIN `" . Machine::TBL_NAME . "` ON `" . Machine::TBL_NAME . "`.`" . Machine::COLUMN_MACHINE_ADDRESS_ID . "`=`" . MachineSlot::TBL_NAME . "`.`machine_address_id` " .
            "LEFT JOIN `" . Transaction::TBL_NAME . "` ON `" . Transaction::TBL_NAME . "`.`" . Transaction::COLUMN_PRODUCT_NAME . "`=`" . Product::TBL_NAME . "`.`" . Product::COLUMN_NAME . "`" .
            " AND `" . Transaction::TBL_NAME . "`.`" . Transaction::COLUMN_AMOUNT . "`=`" . Product::TBL_NAME . "`.`" . Product::COLUMN_PRICE . "` " .
            "WHERE `" . MachineSlot::TBL_NAME . "`.`" . MachineSlot::COLUMN_MACHINE_ID . "` = '" . $id . "'";

        // Check If filter by category ID
        if ($categoryId != null) {
            $query .= " AND  `" . Product::TBL_NAME . "`.`" . Product::COLUMN_CATEGORY_ID . "` IN ($categoryId)";
        }

        $query .= " GROUP BY (`" . MachineSlot::TBL_NAME . "`.`id`) ";

        // Check if sorted by latest
        switch ($sort) {
            case "1":
                // $query .= " ORDER BY `" . MachineSlot::TBL_NAME . "`.`slot_id` DESC";
                $query .= " ORDER BY `" . MachineSlot::TBL_NAME . "`.`" . MachineSlot::COLUMN_SLOT_ID . "` ASC";
                break;

            case "2":
                $query .= " ORDER BY `transaction_count` DESC";
                break;

            case "3":
                $query .= " ORDER BY `" . Product::TBL_NAME . "`.`" . Product::COLUMN_PRICE . "` ASC";
                break;

            case "4":
                $query .= " ORDER BY `" . Product::TBL_NAME . "`.`" . Product::COLUMN_PRICE . "` DESC";
                break;
        }

        return DB::select($query);
    }

    public function buildSelect($table_name, $column_names)
    {
        $buildSelect = '';

        foreach ($column_names as $column_name) {
            $buildSelect .= "$table_name.$column_name AS " . $table_name . "_$column_name,";
        }

        return substr($buildSelect, 0, -1);
    }

    public function getMachineSlotDataViaAddressAndSlot(string $machineAddressId, string $machineSlotId)
    {
        return MachineSlot::where(MachineSlot::COLUMN_MACHINE_ID, $machineAddressId)
            ->where(MachineSlot::COLUMN_SLOT_ID,  $machineSlotId)
            ->first();
    }


    public function checkMachineSlotOutofStock(MachineSlot $data): bool
    {
        return $data->current_count <= 0;
    }

    public function decreaseMachineSlotStock(Transaction $transaction)
    {
        $data = MachineSlot::where(MachineSlot::COLUMN_MACHINE_ID, $transaction->machine_address_id)
            ->where(MachineSlot::COLUMN_SLOT_ID, $transaction->machine_slot_address_id)
            ->first();
        $data->current_count = --$data->current_count;
        $data->update();
        return $data;
    }

    public function insertMachineId(RegisterMachineRequest $request, $userId)
    {
        $data = Machine::create($request->all());
        $this->recordRegisterMachineSlot($userId, $data->machine_address_id);
        return $data;
    }

    public function insertMachineSlot(RegisterMachineSlotRequest $request,  $userId)
    {
        $data = MachineSlot::create($request->all());
        $this->recordRegisterMachineSlotId($userId, $data->slot_id, $data->product_id, $data->machine_address_id);
        return $data;
    }

    public function resetSlotTrait(ResetSlotRequest $request)
    {
        $data = MachineSlot::where(MachineSlot::COLUMN_MACHINE_ID, $request->machine_address_id)
            ->where(MachineSlot::COLUMN_SLOT_ID, $request->slot_id)
            ->first();
        $data->current_count = $data->max_count;
        $data->update();
        return $data;
    }

    public function updateLastUpdated($data)
    {
        $currentDateTime = Carbon::now()
            ->format('Y-m-d H:i:m');
        $data->last_connected = $currentDateTime;
        $data->update();
        return $data;
    }

    public function removeFirstItemSerial($data)
    {
        $data->serial = $this->popFirstItemInArray($data->serial);
        $data->update();
        return $data;
    }

    public function popFirstItemInArray($serial): String
    {
        $array = array_map('trim', explode(',', $serial));

        array_shift($array);

        $stringArray = implode(", ", $array);

        return $stringArray;
    }

    public function updatePrinterStatus($data, $message)
    {
        $data->printer_status = $message;
        $data->update();
        return $data;
    }
}
