<?php 
namespace App\Traits\DB; 

use App\Models\User;
use App\Models\Store;

    trait MachineDashboardTable{
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
        function getStoresUserIdData($id)
        {   
            $stores = Store::where('user_id', $id)->paginate(5);
            return $stores;
        } 
    }
?>