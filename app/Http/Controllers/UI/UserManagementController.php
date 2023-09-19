<?php

namespace App\Http\Controllers\UI;

use App\Helpers\UtilActivityLogging;
use App\Models\Role;
use App\Models\User;
use App\Models\Store;
//use GuzzleHttp\Psr7\Request;
use App\Traits\DB\UserTable;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Models\Machine;
use App\Models\UserStoreList;
use Illuminate\Support\Facades\Session;
use App\Requests\User\RegisterUserManagementRequest;
use App\Traits\DB\RoleTable;
use App\Traits\DB\StoreTable;
use App\Traits\DB\VendingMachineTable;
use Illuminate\Support\Arr;
use Throwable;

class UserManagementController extends Controller
{
    use UserTable, StoreTable, RoleTable, VendingMachineTable;
    public function index(Request $request)
    {
        /*
        Role:
            check models of role and user
        */
        $user_id = auth()->user()->id;
        $getRequestUserId = $request->getID != 0 ? $request->getID : 0;
        if (auth()->user()->role_id == 1) {
            //get all role data
            $roles = $this->getRole();
            //get all stores, this is for display on create new user.
            $createStoreLink = $this->getStores();
            //view all following data
            //$users = $getRequestUserId != 0 ? $this->getUserData($getRequestUserId) : $this->getUserDataPaginate();
            $users = $this->getUserDataPaginate();
            //get all unlink store Data
            //$unlinkStores = $getRequestUserId != 0 ? $this->filteredAllUnlinkStoreFromUser($getRequestUserId) : $this->getStoresPaginate();
            $unlinkStores = $this->getStoresAll();
            //checks if filtered button is clicked, and getRequestUserId != null, if !null = get storeData, else send empty array
            $linkedStores = $getRequestUserId != 0 ? $this->getAllFilteredLinkedStores($getRequestUserId) : [];
            $both = $this->getBothLinkedAndUnlinkedStores($getRequestUserId);
            //get all machine data
            $machines = Machine::all();
            if ($request->ajax()) {
                //
                return response()->json([
                    'status' => 200,
                    'linkedStores' => json_encode($linkedStores),
                    'unlinkStores' => json_encode($unlinkStores),
                    'both' => json_encode($both),
                    //$filteredstores,//json_decode($filteredstores),//json_encode($filteredstores),
                ]);
            }
            UtilActivityLogging::saveUserActivityLog("User accessed the user list", null);
            return view('pages.users.index', compact('users', 'roles', 'createStoreLink', 'machines'));
        } else {
            //remove id == 1
            $roles = $this->getRole()->skip(1);
            //if user is below super admin
            //get filter Store data
            $LinkedStores = $this->getStoresViaUserId($user_id);
            $storesId = $this->convertLinkedStoresQueryToLinkedStoresId($LinkedStores);

            //display user data only
            $users = $this->convertStoresQueryToUsers($this->getStoreUsers($storesId));
            // $users = $this->getUserData($user_id);

            //get all machine data
            $machines = $this->machineData();
            return view('pages.users.index', compact('users', 'roles', 'LinkedStores'));
        }
        /*
        $checkRole = DB::table('users')
                ->join('roles','users.role_id', '=' , 'roles.id')
                ->select('roles.name')
                ->get();
        */
    }
    public function create(RegisterUserManagementRequest $request)
    {
        try {
            $validatedData = $request->validated();
            //check if admin or stock replenisher
            if ($request->role_id > 1) {
                //check if any store is link to user, for newly created admin account
                if ($request->store != null) {
                    //create new user, and get newly created user->id
                    $getAdminNewlyCreatedId = $this->CreateUser($request);
                    //check if user is already existing
                    if ($getAdminNewlyCreatedId != 0) {
                        //save record to userstorelist
                        foreach ($request->store as $store) {
                            $this->LinkStoreToUser($store, $getAdminNewlyCreatedId);
                        }
                        UtilActivityLogging::saveUserActivityLog("User sucessfully added new user", null);
                        //return with message
                        Session::flash('success', "Record has been Added.");
                    } else {
                        //return with message
                        Session::flash('warning', "Record is already existing.");
                    }
                } else {
                    //return with message
                    Session::flash('warning', "Failed user creation, no linked store was found.");
                }
            }
            //for newly created super admin account
            else {
                $getSuperAdminNewlyCreatedId = $this->CreateUser($request);
                if ($getSuperAdminNewlyCreatedId != 0) {
                    //return with message success
                    Session::flash('success', "Record has been Added.");
                } else {
                    //return with message success
                    Session::flash('warning', "Record is already existing.");
                }
            }

            //display return
            return redirect()->route('usermanagement.index');
        } catch (Throwable $e) {
            return redirect()->back()->withErrors($e->validator)->withInput();
        }
    }

    public function update(Request $request)
    {
        //get request input
        $ID = $request->ID;
        $firstName = $request->firstName;
        $lastName = $request->lastName;
        $emailAddress = $request->emailAddress;
        $assignRole = $request->assignRole;
        $assignStatus = $request->assignStatus;
        $forLinkToUser = $request->forLinkToUser;
        $unlinkStore = $request->removeStore;
        //identify if user role has been change
        $changeRoleResult = $this->IdentifyIfUserHasBeenChangeRole($ID, $assignRole);
        if ($changeRoleResult == 'true') {
            //update user data
            $this->UpdateUser($ID, $firstName, $lastName, $emailAddress, $assignStatus, $assignRole);
            //if change to super admin
            //check all linked store from user, then automatic unlinked all store from user
            $this->automaticUnlinkStoreFromUser($ID);
            //display message
            UtilActivityLogging::saveUserActivityLog("User sucessfully updated a user", null);
            Session::flash('success', "Record of: <h3>" . $emailAddress . "</h3> has been successfully change role to Super Admin.");
            if ($forLinkToUser != null) {
                //linkstore to user
                foreach ($forLinkToUser as $toLink) {
                    $this->LinkStoreToUser($toLink, $ID);
                }
                //return with message success
                Session::flash('success', "Record of: <h3>" . $emailAddress . "</h3> has been successfully updated.");
            }
        } else {
            //update -> user
            $this->UpdateUser($ID, $firstName, $lastName, $emailAddress, $assignStatus, $assignRole);
            if ($forLinkToUser != null) {
                //linkstore to user
                foreach ($forLinkToUser as $toLink) {
                    $this->LinkStoreToUser($toLink, $ID);
                }
                //return with message success
                Session::flash('success', "Record of: <h3>" . $emailAddress . "</h3> has been successfully updated.");
            }
            if ($unlinkStore == 0 || $unlinkStore == null) {
                //display message
                Session::flash('success', "Record of: <h3>" . $emailAddress . "</h3> has been successfully change role to Admin.");
            }
            if ($unlinkStore != 0 && $unlinkStore != null) {
                //unlinkstore to user
                foreach ($unlinkStore as $unlink) {
                    $checkIfFailedUnlink = $this->unLinkStoreToUser($unlink, $ID);
                }
                //if linked remaining store is more than one, display this.
                if ($checkIfFailedUnlink == 'success') {
                    //return with message success
                    Session::flash('success', "Record of: <h3>" . $emailAddress . "</h3> has been successfully updated.");
                }
                //if linked remaining store is one, display this.
                else if ($checkIfFailedUnlink == 'cannot-update') {
                    //return with message success
                    Session::flash('warning', "Linked Store cannot be unlink. Atleast one link store to each user.");
                } else {
                    Session::flash('success', "Record of: <h3>" . $emailAddress . "</h3> has been successfully updated.");
                }
            }
        }
        //display return
        return redirect()->route('usermanagement.index');
    }

    public function delete(Request $request)
    {
        $this->deleteUser($request->id);

        $user_id = auth()->user()->id;
        $getRequestUserId = $request->getID != 0 ? $request->getID : 0;
        if (auth()->user()->role_id == 1) {
            //get all role data
            $roles = $this->getRole();
            //get all stores, this is for display on create new user.
            $createStoreLink = $this->getStores();
            //view all following data
            //$users = $getRequestUserId != 0 ? $this->getUserData($getRequestUserId) : $this->getUserDataPaginate();
            $users = $this->getUserDataPaginate();
            //get all unlink store Data
            //$unlinkStores = $getRequestUserId != 0 ? $this->filteredAllUnlinkStoreFromUser($getRequestUserId) : $this->getStoresPaginate();
            $unlinkStores = $this->getStoresAll();
            //checks if filtered button is clicked, and getRequestUserId != null, if !null = get storeData, else send empty array
            $linkedStores = $getRequestUserId != 0 ? $this->getAllFilteredLinkedStores($getRequestUserId) : [];
            $both = $this->getBothLinkedAndUnlinkedStores($getRequestUserId);
            //get all machine data
            $machines = Machine::all();
            if ($request->ajax()) {
                //
                return response()->json([
                    'status' => 200,
                    'linkedStores' => json_encode($linkedStores),
                    'unlinkStores' => json_encode($unlinkStores),
                    'both' => json_encode($both),
                    //$filteredstores,//json_decode($filteredstores),//json_encode($filteredstores),
                ]);
            }
            UtilActivityLogging::saveUserActivityLog("User sucessfully deleted a user", null);
            return view('pages.users.index', compact('users', 'roles', 'createStoreLink', 'machines'));
        } else {
            //remove id == 1
            $roles = $this->getRole()->skip(1);
            //if user is below super admin
            //display user data only
            $users = $this->getUserData($user_id);
            //get filter Store data
            $LinkedStores = $this->getStoresViaUserId($user_id);
            //get all machine data
            $machines = $this->machineData();
            return view('pages.users.index', compact('users', 'roles', 'LinkedStores'));
        }
    }
}
