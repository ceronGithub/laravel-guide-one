<?php

namespace App\Traits\DB;

use App\Models\Machine;
use App\Http\Controllers\UI\VendingMachineController;
use App\Requests\VendingMachine\RegisterVendingMachineRequest;


trait VendingMachineTable{


    function machineData()
    {
        return Machine::all();
    }

    function RegisterVendingMachine(RegisterVendingMachineRequest $request){
        $machine = Machine::create($request->all());
        return $machine;
    }

    function DeleteVendingMachine($id)
    {
        //check data based on passed Id
        $forDeletion = Machine::where(['id' => $id])->delete();
        return $forDeletion;
    }

    function getSelectedVendingMachineDetailViaIndex($id)
    {
        return Machine::where("id", $id)->first();
    }

    function getSelectedVendingMachineDetail($id)
    {
        return Machine::where(Machine::COLUMN_STORE_ID, $id)->first();
    }

    function UpdateVendingMachine($id, $name, $address, $desc)
    {
        $update = Machine::where('id', $id)->first();
        $update->name = $name != null ? $name : $update->name;
        $update->machine_address_id = $address != null ? $address : $update->machine_address_id;
        $update->desc = $desc != null ? $desc : $update->desc;
        $update->save();
        return $update;
    }

    function getMachineAddress($id)
    {
        return Machine::where('id', $id)->first(['machine_address_id'])->machine_address_id;
    }
}

?>
