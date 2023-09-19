<?php

namespace App\Http\Controllers\UI;

use App\Helpers\UtilActivityLogging;
use Throwable;
use Illuminate\Http\Request;
use App\Traits\DB\MachineTable;
use App\Traits\DB\ProductTable;
use App\Http\Controllers\Controller;
use App\Models\MachineSlot;
use App\Traits\DB\VendingMachineTable;
use Illuminate\Support\Facades\Session;
use App\Requests\Machine\RegisterMachineSlotRequest;
use App\Requests\Machine\UpdateMachineSlotRequest;
use App\Traits\DB\StoreTable;

class MachineSlotController extends Controller
{
    use MachineTable, ProductTable, VendingMachineTable, StoreTable;
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($machineId)
    {
        $machineSlots = $this->getMachineSlotData1($machineId);
        dd($machineSlots);
        $machine = $this->getMachineData($machineId);
        $products = $this->getProductsData();
        $store_id = $this->StoreID($machineId);
        UtilActivityLogging::saveUserActivityLog("User accessed the list of machine slots of vending " . $machine->name . ".", null);
        return view('pages.stores.machines.machine-details', compact('machine', 'machineId', 'machineSlots', 'products', 'store_id'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(RegisterMachineSlotRequest $request, $machineSlotId)
    {
        // dd($request);
        try {
            $request->validated();

            $slot_id = $request->input('slot_id');
            $current_count = $request->input('current_count');
            $max_count = $request->input('max_count');
            $getSerialData = $request->all()["serial"];

            if (!$this->checkMachineSlotExists($slot_id, $machineSlotId)) {
                if ($max_count >= $current_count && !in_array(null,$getSerialData)) {
                    $requestData = $request->all();
                    $requestData["serial"] = implode(",",$request->all()["serial"]);
                    $machineSlot = $this->RegisterMachineSlot($requestData);
                    $machine = $this->getMachineData($machineSlot->machine_address_id);
                    Session::flash('success', "Successfully created new machine slot.");
                    UtilActivityLogging::saveUserActivityLog("Added new machine slot with id " .
                        $slot_id . " for vending machine " .
                        $machine->name . ".",  ["machine_slot" => $machineSlot->toArray()], config('logging.LOG_NAMES.USER_CREATE_MACHINE_SLOT'));
                    return redirect()->route('machine-slots.index', $machineSlotId);
                }
                else if(in_array(null,$getSerialData))
                {
                    Session::flash('warning', "Update unsuccessful. Due to empty serial no.");
                    return redirect()->route('machine-slots.index', ['machineSlotId' => $machineSlotId]);
                } 
                else {
                    //display text
                    Session::flash('warning', "Update unsuccessful. Max-count should be higher than current-count");
                    return redirect()->route('machine-slots.index', $machineSlotId);
                }
            } else {
                //display text
                Session::flash('warning', "Update unsuccessful. Slot ID already existing");
                return redirect()->route('machine-slots.index', $machineSlotId);
            }
        } catch (Throwable $e) {
            return redirect()->route('machine-slots.index', $machineSlotId);
        }

    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateMachineSlotRequest $request, $machineAddress)
    {
        // dd($request);
        try {
            $validatedData = $request->validated();

            $id = $validatedData['index'];
            $max_count = $validatedData['max-count'];
            $current_count = $validatedData['current-count'];
            $product_id = $validatedData['product-id'];
            $stock = $validatedData['stock_alert'];
            $spare = $validatedData['spare-quantity'];
            // serial array collection
            $getSerialData = $request->all()["serial"];
            // $serialArrayElement = array_values($serialChecker);
            // check if all array elements have value         
            // in_array(null,$getSerialData)                   
            if ($max_count >= $current_count && !in_array(null,$getSerialData)) {
                // $serial = in_array("serial",$request->all()) ? implode(",",$request->all()["serial"]) : null;
                $serial = implode(",",$request->all()["serial"]);
                //update machineslot, based on passed Id
                $machineSlot = $this->UpdateMachineSlot($id, $product_id, $max_count, $current_count, $stock, $serial, $spare);
                $machine = $this->getMachineData($machineSlot->machine_address_id);
                //display text
                Session::flash('success', "Record has been updated.");
                UtilActivityLogging::saveUserActivityLog("Updated machine slot ID " .
                    $machineSlot->slot_id . " for vending machine " .
                    $machine->name . ".",  ["machine_slot" => $machineSlot->toArray()], config('logging.LOG_NAMES.USER_UPDATED_MACHINE_SLOT'));
                return redirect()->route('machine-slots.index', ['machineSlotId' => $machineAddress]);
            }
            else if(in_array(null,$getSerialData))
            {
                Session::flash('warning', "Update unsuccessful. Due to empty serial no.");
                return redirect()->route('machine-slots.index', ['machineSlotId' => $machineAddress]);
            } 
            else {
                //display text
                Session::flash('warning', "Update unsuccessful. Due to max-count should be higher than Current-count.");
                return redirect()->route('machine-slots.index', ['machineSlotId' => $machineAddress]);
            }
        } catch (Throwable $e) {
            return redirect()->back()->withErrors($e->validator)->withInput();
        }
    }
    public function updateSparePart(Request $request, $machineAddress)
    {
        try {        
            // dd($request);
            $id = $request->input('id');
            $editSparePartQtyTo = $request->input('totalSpareParts');
            $currentQty = $request->input('currentQry');
            if($editSparePartQtyTo > $currentQty)
            {
                $machineSlot = $this->UpdateMachineSlotSpareParts($id, $editSparePartQtyTo);
                $machine = $this->getMachineData($machineSlot->machine_address_id);
                Session::flash('success', "Record has been updated.");
                UtilActivityLogging::saveUserActivityLog("Updated machine slot ID " .
                    $machineSlot->slot_id . " for vending machine " .
                    $machine->name . ".",  ["machine_slot" => $machineSlot->toArray()], config('logging.LOG_NAMES.USER_UPDATED_MACHINE_SLOT'));
                return redirect()->route('machine-slots.index', ['machineSlotId' => $machineAddress]);
            }
            else {
                //display text
                Session::flash('warning', "Update unsuccessful. Due to max-count should be higher than Current-count.");
                return redirect()->route('machine-slots.index', ['machineSlotId' => $machineAddress]);
            }
            // $id = $validatedData['index'];
            // $max_count = $validatedData['max-count'];
            // $current_count = $validatedData['current-count'];
            // $product_id = $validatedData['product-id'];
            // $stock = $validatedData['stock_alert'];
            // $spare = $validatedData['spare-quantity'];
            // if ($max_count >= $current_count) {
            //     $serial = in_array("serial",$request->all()) ? implode(",",$request->all()["serial"]) : null;
            //     //update machineslot, based on passed Id
            //     $machineSlot = $this->UpdateMachineSlot($id, $product_id, $max_count, $current_count, $stock,$serial, $spare);
            //     $machine = $this->getMachineData($machineSlot->machine_address_id);
            //     //display text
            //     Session::flash('success', "Record has been updated.");
                // UtilActivityLogging::saveUserActivityLog("Updated machine slot ID " .
                //     $machineSlot->slot_id . " for vending machine " .
                //     $machine->name . ".",  ["machine_slot" => $machineSlot->toArray()], config('logging.LOG_NAMES.USER_UPDATED_MACHINE_SLOT'));
                // return redirect()->route('machine-slots.index', ['machineSlotId' => $machineAddress]);
            // } 
            // else {
            //     //display text
            //     Session::flash('warning', "Update unsuccessful. Due to max-count should be higher than Current-count.");
            //     return redirect()->route('machine-slots.index', ['machineSlotId' => $machineAddress]);
            // }
        } catch (Throwable $e) {
            return redirect()->back()->withErrors($e->validator)->withInput();
        }
    }

    public function delete(Request $request)
    {
        $machineSlotId = $this->getMachineSlotDataByIndex($request->id);
        $machine = $this->getMachineData($machineSlotId->machine_address_id);
        try {
            $this->deleteMachineSlot($machineSlotId->id);
            UtilActivityLogging::saveUserActivityLog("Deleted machine slot ID " .
                $machineSlotId->slot_id . " for vending machine " .
                $machine->name . ".", ["machine_slot" => $machineSlotId->toArray()], config('logging.LOG_NAMES.USER_DELETED_MACHINE_SLOT'));
            Session::flash('success', "Record has been deleted.");
        } catch (Throwable $e) {
            //display message
            Session::flash('missing', "Something went wrong. Please try again");
            //display return
            return redirect()->route('store.index');
        }

        return $this->index($machineSlotId->machine_address_id);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
