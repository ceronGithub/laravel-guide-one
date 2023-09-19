<?php

namespace App\Http\Controllers\UI;

use App\Helpers\UtilActivityLogging;
use Throwable;
use App\Models\Store;

use Illuminate\Http\Request;
use App\Traits\DB\StoreTable;

use App\Traits\DB\MachineTable;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Support\Facades\Session;
use App\Requests\Store\RegisterStoreRequest;
use App\Traits\DB\UserTable;
use App\Traits\DB\VendingMachineTable;

class StoreListController extends Controller
{
    use StoreTable, MachineTable, VendingMachineTable, UserTable;
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    //check middleware/authenticate : note!
    public function index()
    {
        if (auth()->user()->role_id != 1)
            $userStores = $this->getStoresViaUserId(auth()->user()->id);
        else
            $userStores = $this->getStores();

        UtilActivityLogging::saveUserActivityLog("User accessed the list of stores.", null);

        return view('pages.stores.index', compact('userStores'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(RegisterStoreRequest $request)
    {
        try{
            $request->validated();
            $storeId = $this->createStore($request)->id;
            $this->LinkStoreToUser($storeId, auth()->user()->id);
            $store = $this->getStoreViaId($storeId);
            Session::flash('success', "Successfully created new store");
            UtilActivityLogging::saveUserActivityLog("User successfully created new store named " . $store->name .".", ["store" => $store->toArray()], config('logging.LOG_NAMES.USER_CREATE_STORE'));
            return redirect()->route('store.index');

        } catch (Throwable $e) {
            return redirect()->back()->withErrors($e->validator)->withInput();
        }
    }
    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function __invoke(Store $store)
    {
    }

    public function show($id)
    {
        return redirect()->route('machine.index', $id);
    }

    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        $id = $request->input('store-id');
        $name = $request->input('name');
        $desc = $request->input('desc');
        if ($id == null || $name == null || $desc == null) {
            Session::flash('missing', "temporary error was occur. Please try again");
        } else {
            try {
                $updated = $this->updateStore($id, $name, $desc);
                UtilActivityLogging::saveUserActivityLog("User successfully updated a store named ". $updated->name . ".", ["store" => $updated->toArray()], config('logging.LOG_NAMES.USER_UPDATED_STORE'));
                Session::flash('success', "Update Successful.");
            } catch (Throwable $e) {
                Session::flash('missing', "temporary error was occur. Please try again");
            }
        }
        return $this->index(); //display return
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Product $product)
    {
        //

    }
}
