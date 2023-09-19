<?php

namespace App\Traits\DB;

use App\Models\User;

use App\Models\Store;
use App\Models\Machine;
use App\Requests\Store\RegisterStoreRequest;
use App\Http\Controllers\UI\StoreListController;
use App\Models\UserStore;
use App\Models\UserStoreList;

trait StoreTable
{
    function getStores()
    {
        return Store::paginate(10);
    }

    function getStoresAll()
    {
        return Store::all();
    }

    function updateStore($id, $name, $desc)
    {
        $update = Store::where('id', $id)->first();
        $update->name = $name != null ? $name : $update->name;
        $update->desc = $desc != null ? $desc : $update->desc;
        $update->save();
        return $update;
    }

    function getStoreId($id)
    {
        //check user data, ang get user ID
        $getUserId = User::where('id', $id)->first()
            ? User::where('id', $id)->first(['id'])->id
            : null;
        //check if passed ID existing on store
        $checkStoreIsExisting = Store::where('user_id', $getUserId)->first()
            ? Store::where('user_id', $getUserId)->first('user_id')->user_id
            : null;
        return $checkStoreIsExisting;
    }

    function getStoreUserIdData($id)
    {
        $store = Store::where('user_id', $id)->firstOrFail();
        return $store;
    }

    public function getStoresViaUserId ($user_id)
    {
        // Get All Stores
        return Store::wherehas("userStore", function ($query) use ($user_id) {
            // Filter it by user id
            if ($user_id != null) {
                $query->where(UserStoreList::COLUMN_USER_ID, $user_id);
            }
        })->paginate(10);
    }

    public function getStoreViaUserId($storeId, $user_id)
    {
        // Get All Stores
        return Store::wherehas("userStore", function ($query) use ($user_id) {
            // Filter it by user id
            if ($user_id != null) {
                $query->where(UserStoreList::COLUMN_USER_ID, $user_id);
            }
        })->where('id', $storeId)->firstOrFail();
    }

    function getStore($index)
    {
        $store = Store::where('id', $index)->firstOrFail();
        return $store;
    }

    function getStoreViaId($id)
    {
        $store = Store::where('id', $id)->firstOrFail();
        return $store;
    }

    public function getBothLinkedAndUnlinkedStores($user_id)
    {
        // Get All Stores
        return Store::wherehas("userStore", function ($query) use ($user_id) {
            // Filter it by user id
            if ($user_id != null) {
                $query->where(UserStoreList::COLUMN_USER_ID, $user_id);
            }
        })->orwhereDoesntHave("userStore", function ($query) use ($user_id) {
            $query->where(UserStoreList::COLUMN_USER_ID, $user_id);
        })->get();
    }

    public function getAllFilteredLinkedStores($user_id)
    {
        // Get All Stores
        return Store::wherehas("userStore", function ($query) use ($user_id) {
            // Filter it by user id
            if ($user_id != null) {
                $query->where(UserStoreList::COLUMN_USER_ID, $user_id);
            }
        })->get();//->paginate(10);
    }

    function filteredAllUnlinkStoreFromUser($user_id)
    {
        // Get All Stores
        return Store::whereDoesntHave("userStore", function ($query) use ($user_id) {
            // Filter it by user id
            if ($user_id != null) {
                $query->where(UserStoreList::COLUMN_USER_ID, $user_id);
            }
        })->get();
    }

    function createStore(RegisterStoreRequest $request)
    {
        //create new store
        $store = Store::create($request->all());
        //return newly created store
        return $store;
    }

    function getMachinesStoreIdData($id)
    {
        //search ID in vendingmachine table, based on passed id
        $machines = Machine::where('store_id', $id)->paginate(5);
        //return value
        return $machines;
    }
    function getMachineStoreIdData($id)
    {
        //search id in vendingmachine table based on passed ID
        $machine = Machine::where('store_id', $id)->first();
        //return all vendingmachine data base on passed ID
        return $machine;
    }
}
