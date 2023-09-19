<?php

namespace App\Traits\DB;

use App\Models\User;
use App\Models\Machine;
use App\Models\Product;
use App\Models\Category;
use App\Models\MachineSlot;
use App\Models\Transaction;
use App\Models\UserActivity;
use App\Models\UserStoreList;
use Illuminate\Support\Facades\DB;
use App\Requests\Machine\ResetSlotRequest;
use App\Requests\Machine\RegisterMachineRequest;
use App\Requests\Machine\RegisterMachineSlotRequest;
use App\Requests\User\RegisterUserManagementRequest;
use App\Http\Controllers\UI\UserManagementController;

trait UserTable
{
    // use StoreTable;

    public function getUserDataPaginate()
    {
        return User::paginate(10);
    }

    public function getStoreUsers($storeList)
    {
        return UserStoreList::with("user")
            ->whereHas("user", function($query){
                $query->whereIn(User::COLUMN_ROLE_ID, [2,3]);
            })
            ->whereIn(UserStoreList::COLUMN_USER_ID, $storeList)
            ->groupBy(UserStoreList::COLUMN_USER_ID)
            ->get();
    }

    public function convertLinkedStoresQueryToLinkedStoresId($linkedStores)
    {
        $linkedStoresIds = null;
        for ($index = 0; $index < count($linkedStores); $index++) {
            $linkedStoresIds[$index] = $linkedStores[$index]->id;
        }
        return $linkedStoresIds;
    }
    public function convertStoresQueryToUsers($stores)
    {
        $userList = null;
        for ($index = 0; $index < count($stores); $index++) {
            $userList[$index] = $stores[$index]->user;
        }
        return $userList;
    }

    public function getUserDataAll()
    {
        return User::all();
    }

    public function getUserData($user_id)
    {
        return User::where("id", $user_id)
            ->get();
    }

    public function registerUser($data)
    {
        return User::create($data);
    }

    public function CreateUser(RegisterUserManagementRequest $request)
    {
        $fname = $request->first_name;
        $lname = $request->last_name;
        $uname = $request->username;
        $role = $request->role_id;
        $active = $request->active;
        $email = $request->email;
        $password = $request->password;
        $isUserExisting = User::where(['first_name' => $fname, 'last_name' => $lname, 'username' => $uname])->first();

        if ($isUserExisting == null) {

            //create new User
            $getId = User::create($request->getValues())->id;
            return $getId;
        } else {
            return 0;
        }
    }

    public function IdentifyIfUserHasBeenChangeRole($id, $previousRole)
    {
        $UserDataBeforeUpdate = User::where('id', $id)->first();
        //check if user role has been change to super admin
        if ($UserDataBeforeUpdate->role_id != $previousRole) {
            //return true, admin account role has been change to super admin
            return true;
        } else {
            return false;
        }
    }

    public function automaticUnlinkStoreFromUser($id)
    {
        $unLinkedStores = UserStoreList::where('user_id', $id)->get()->toArray();
        foreach ($unLinkedStores as $storeDetail) {
            $updateStoreList = UserStoreList::where('id', $storeDetail['id'])->first();
            $updateStoreList->user_id = $id;
            $updateStoreList->store_id = 0;
            $updateStoreList->save();
        }
        return true;
    }

    public function UpdateUser($id, $fname, $lname, $email, $status, $role)
    {
        //check if user is existing
        $update = User::where('id', $id)->first();
        $update->first_name =  $fname != null ? $fname : $update->first_name;
        $update->last_name =  $lname != null ? $lname : $update->last_name;
        $update->email =  $email != null ? $email : $update->email;
        $update->active =  $status != null ? $status : $update->active;
        $update->role_id =  $role != null ? $role : $update->role_id;
        return $update->save();
    }

    public function LinkStoreToUser($toLink, $userID)
    {
        $linkStoreToUser = new UserStoreList();
        $linkStoreToUser->user_id = $userID;
        $linkStoreToUser->store_id = $toLink;
        return $linkStoreToUser->save();
    }
    public function unLinkStoreToUser($unLink, $userID)
    {
        $countAllLinkStoreToUser = UserStoreList::where(['user_id' => $userID])->count();
        //dd($countAllLinkStoreToUser);
        //if linked store is more than one
        if ($countAllLinkStoreToUser > 1 && $unLink != 0 && $unLink != null) {
            //note: if unlink store from user account. data will not be deleted, but instead replace user_id and store_id to 0.
            $unlinkStoreToUser = UserStoreList::where(['user_id' => $userID, 'store_id' => $unLink])->first();
            $unlinkStoreToUser->user_id = $userID;
            $unlinkStoreToUser->store_id = 0;
            $unlinkStoreToUser->save();
            return "success";
        }
        //if linked store is equal to one
        else if ($countAllLinkStoreToUser == 1) {
            return "cannot-update";
        }
        //unlink passed 0 nor null, data
        else {
            return "no-changes";
        }
    }

    public function deleteUser($id)
    {
        $data = User::where("id", $id)
            ->delete();
        return $data;
    }
}
