<?php

namespace App\Http\Controllers\UI;

use App\Helpers\UtilActivityLogging;
use Throwable;

use Illuminate\Http\Request;
use App\Traits\DB\MachineTable;
use App\Traits\DB\ProductTable;
use App\Http\Controllers\Controller;
use App\Traits\DB\VendingMachineTable;
use Illuminate\Support\Facades\Session;
use App\Requests\VendingMachine\RegisterVendingMachineRequest;
use App\Traits\DB\StoreTable;
use Illuminate\Support\Facades\Redirect; //allows redirect to work on your page

class VendingMachineController extends Controller
{
    use MachineTable, ProductTable, VendingMachineTable, StoreTable;
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function index($id)
    {
        if (auth()->user()->role_id != 1)
            $store = $this->getStoreViaUserId($id, auth()->user()->id);
        else
            $store = $this->getStore($id);

        $machines = $this->getMachineListWithPaginate($id);
        UtilActivityLogging::saveUserActivityLog("User accessed the list of vending machine of " . $store->name . ".", null);
        return view('pages.stores.machines.index', compact('machines', 'store'));
    }
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(RegisterVendingMachineRequest $request, $store_id)
    {
        dd($request);
        try {
            $request->validated();
            //register method
            $machine = $this->RegisterVendingMachine($request);
            //return with message warning
            Session::flash('success', "Record has been Added.");
            UtilActivityLogging::saveUserActivityLog("User added new vending machine named " . $machine->name . ".", ["machine" => $machine->toArray()], config('logging.LOG_NAMES.USER_CREATE_MACHINE'));
            //display return
            return redirect()->route('store.show', ['id' => $store_id]);
        } catch (Throwable $e) {
            //return with message warning
            Session::flash('missing', "Failed to add new item, due to lack of information or details is already existing. Please Complete all needed information. Thank you!");
            //display return
            return redirect()->route('store.show', ['id' => $store_id]);
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $store_id)
    {
        try {
            //
            $id = $request->input('id');
            $name = $request->input('name');
            $desc = $request->input('desc');
            $machine_address_id = $request->input('machine_address_id');
            //update vendingmachine
            $machine = $this->UpdateVendingMachine($id, $name,  $machine_address_id, $desc);
            //return with message warning
            UtilActivityLogging::saveUserActivityLog("User successfully updated the vending machine named " . $machine->name . ".", ["machine" => $machine->toArray()], config('logging.LOG_NAMES.USER_UPDATED_MACHINE'));
            Session::flash('success', "Update Successful.");
        } catch (Throwable $e) {
            //return with message warning
            Session::flash('missing', "temporary error was occur. Please try again");
        }
        //display return
        return $this->index($store_id);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function delete(Request $request)
    {

        $machine = $this->getMachineByIndex($request->id);
        try {
            $this->deleteMachine($request->id);
            UtilActivityLogging::saveUserActivityLog("User successfully deleted the vending machine named " . $machine->name . ".",  ["machine" => $machine->toArray()], config('logging.LOG_NAMES.USER_DELETED_MACHINE'));
            Session::flash('success', "Record has been deleted.");
        } catch (Throwable $e) {
            //display message
            Session::flash('missing', "Something went wrong. Please try again");
            //display return
            return redirect()->route('machine.index', $machine->store_id);
        }
        //display return
        return redirect()->route('machine.index', $machine->store_id);
    }
}
