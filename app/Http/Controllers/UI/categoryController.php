<?php

namespace App\Http\Controllers\UI;

use Throwable;
use Illuminate\Http\Request;
use App\Traits\DB\CategoryTable;
use App\Helpers\UtilActivityLogging;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use App\Requests\Category\RegisterCategoryRequest;

class categoryController extends Controller
{
    // call trait
    use CategoryTable;
    /**
     * Create a new controller instance.
     * prevent user from going to this page without proper login
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }
    // Calls 
    public function index()
    {
        if(Auth::check())
        {
            // Get category list
            $categories = $this->CategoryList();
            return view('pages.categories.index', compact('categories'));
        }        
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(RegisterCategoryRequest $request)
    {
        try{
            $request->validated();
            $category = $this->createCategory($request);                        
            Session::flash('success', "Successfully created new store");
            UtilActivityLogging::saveUserActivityLog("User successfully created new store named " . $category->name .".", ["store" => $category->toArray()], config('logging.LOG_NAMES.USER_CREATE_STORE'));
            return redirect()->route('category.index');

        } catch (Throwable $e) {
            return redirect()->back()->withErrors($e->validator)->withInput();
        }
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
                $updated = $this->updateCategory($id, $name, $desc);
                UtilActivityLogging::saveUserActivityLog("User successfully updated a category named ". $updated->name . ".", ["store" => $updated->toArray()], config('logging.LOG_NAMES.USER_UPDATED_STORE'));
                Session::flash('success', "Category named " . $updated->name. "Has been successful updated.");
                return redirect()->route('category.index');
            } catch (Throwable $e) {
                Session::flash('missing', "temporary error was occur. Please try again");
                return redirect()->route('category.index');
            }
        }
        return $this->index(); //display return
    }    

    public function delete(Request $request)
    {
        $id = $request->input('id');
        // dd($request);
        try {
            $this->deleteCategory($id);
            // UtilActivityLogging::saveUserActivityLog("Deleted machine slot ID " .
            //     $machineSlotId->slot_id . " for vending machine " .
            //     $machine->name . ".", ["machine_slot" => $machineSlotId->toArray()], config('logging.LOG_NAMES.USER_DELETED_MACHINE_SLOT'));
            Session::flash('success', "Record has been deleted.");
            return redirect()->route('category.index');
        } catch (Throwable $e) {
            //display message
            Session::flash('missing', "temporary error was occur. Please try again");
            //display return
            return redirect()->route('category.index');
        }        
    }
}
