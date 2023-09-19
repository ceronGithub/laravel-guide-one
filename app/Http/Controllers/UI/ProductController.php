<?php

namespace App\Http\Controllers\UI;

use App\Helpers\UtilActivityLogging;
use Exception;
use Throwable;
use App\Models\User;
use App\Models\Store;
use App\Models\Machine;
use Illuminate\Support\Arr;

use Illuminate\Http\Request;
use App\Traits\DB\StoreTable;

use App\Traits\DB\MachineTable;

use App\Traits\DB\ProductTable;
use App\Resources\ProductResource;
use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use App\Requests\Store\RegisterStoreRequest;
use App\Requests\Product\RegisterProductRequest;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Facade\Ignition\Exceptions\ViewExceptionWithSolution;
use PHPUnit\Framework\MockObject\ReturnValueNotConfiguredException;
use Illuminate\Support\Facades\Redirect; //allows redirect to work on your page
use Illuminate\Support\Facades\File;


class ProductController extends Controller
{
    use ProductTable;
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    //check middleware/authenticate : note!
    public function index(Request $request)
    {
        //check user if logged in
        if (Auth::check()) {
            $products = $this->getProductsData($request->input('getCategory'));
            $categories = $this->getCategories();
            UtilActivityLogging::saveUserActivityLog("User accessed the list of products", null);
            return view('pages.products.index', compact('products', 'categories'));
        }
    }

    public function create(RegisterProductRequest $request)
    {

        try{
            $request->validated();
            $checkedID = $this->DuplicateIDChecker($request->input('product_code'));
            if(!($checkedID))
            {
                $productNames = 0;
                $filePath = [];
                foreach($request->file('image') as $file)
                {
                    //increase in every loop.
                    $productNames++;
                    //get file type
                    $extension = $file->extension();
                    //whole file w/ file type
                    $imageName = $this->generateProductImageName($request->name.$productNames) . '.' . $extension;
                    //save image to local folder. project/app/public
                    $file->move(public_path('storage/uploads/products/'), $imageName);
                    $filePath[] = '"storage\/uploads\/products\/'.$imageName.'"';
                }
                $imagePath = '['.implode(',', $filePath).']';
                $name = $request->input('name');
                $desc = $request->input('desc');
                $price = $request->input('price');
                $category = $request->input('category_id');
                $feature = $request->input('feature');
                $specification = $request->input('specification');
                $productcode = $request->input('product_code');
                $newProduct = $this->RegisterProduct($name, $desc, $price, $category, $feature, $specification, $productcode, $imagePath);
                UtilActivityLogging::saveUserActivityLog("User successfully added a product.", ["product" => $newProduct->toArray()], config('logging.LOG_NAMES.USER_CREATE_PRODUCT'));
                Session::flash('success', "Successfully added a product.");
                //get data of the ff: categories, products
                $products = $this->getProductsData();
                $categories = $this->getCategories();
                //return view('products', compact('products', 'categories'));//THIS CAUSE DUPLICATION OF IMAGE
                return redirect()->route('products')->with(compact('products', 'categories')); //FIEX DUPLICATION OF IMAGE
            }
            else
            {
                Session::flash('missing', "Product ID is existing. Please try again");
                //return view('products', compact('products', 'categories'));//THIS CAUSE DUPLICATION OF IMAGE
                return redirect()->route('products.index'); //FIEX DUPLICATION OF IMAGE
            }
        }
        catch(Throwable $e)
        {
            //display return
            return redirect()->route('products.index');
        }
    }

    function generateProductImageName(string $productName): string
    {
        $currentDateTime = Carbon::now()->toDateTimeString();

        return hash('sha256', $productName . '_' . $currentDateTime);
    }


    public function update(Request $request, $id)
    {
        try{
            $name = $request->input('product-name');
            $description = $request->input('description');
            $price = $request->input('price');
            $categoryId = $request->input('category-id');
            $feature = $request->input('feature');
            $specification = $request->input('specification');
            $productCode = $request->input('product-code');

            // search database by productCode value ang get images
            $imgStoreInDB = $this->getCurrentImagesOfProduct($productCode);
            // search database by productCode value ang get name
            $productName = $this->getProductName($productCode);

            $imgTmp = $imgStoreInDB;

            // selected image for deletion.
            $forImageDeletion = $request->input('deleteImage');

            // checks if theres an image for uploading
            if(!empty($request->file('image')))
            {

                //image update
                //get old image
                $productNames = 0;
                $oldPath = [];
                foreach($imgTmp as $file)
                {
                    $productNames++;
                    $oldPath[] = '"'.$file.'"';
                }
                //create new image
                $filePath = [];
                foreach($request->file('image') as $file)
                {
                    $productNames++;//increase each loop
                    $extension = $file->extension();//get each file type of the image/s
                    //$imageName = time() . '-' . $request->name.$productNames.'.' . $extension;
                    $imageName = $this->generateProductImageName($request->name.$productNames).'.' . $extension;
                    $file->move(public_path('storage/uploads/product/'), $imageName);
                    $filePath[] = '"storage\/uploads\/product\/'.$imageName.'"';
                }
                //get the old and new image path
                $imagePath = implode(',', $oldPath).','.implode(',', $filePath);
                //image path for new and old
                $imagePath = '['.$imagePath.']';


                //check if there is for deletion of image
                if($forImageDeletion != "No-Image-Deletion")
                {
                    //check image to public path
                    if(File::exists(public_path($imgStoreInDB[$forImageDeletion])))
                    {
                        //all images from database, based of passed ID
                        $arrayIndex = $imgStoreInDB;
                        //delete image from public path
                        File::delete($imgStoreInDB[$forImageDeletion]);
                        //remove the image from database
                        unset($arrayIndex[$forImageDeletion]);
                        //all remaining image/s
                        $remainingArray = array_values($arrayIndex);
                        //save records
                        $updatedProduct = $this->updateProduct($productCode, $name, $price, $categoryId, $specification, $feature, $description, $remainingArray);
                        //display text
                        UtilActivityLogging::saveUserActivityLog("User successfully updated product details.", ["product" => $updatedProduct->toArray()], config('logging.LOG_NAMES.USER_UPDATED_PRODUCT'));
                        Session::flash('success', "Product detail has been updated.");
                        $categories = $this->getCategories();
                        $products = $this->getProductsData();
                        return redirect()->route('products.index')->with(compact('products', 'categories')); //FIEX DUPLICATION OF IMAGE
                    }
                }
                else
                {
                    //save records
                    $updatedProduct = $this->updateProduct($productCode, $name, $price, $categoryId, $specification, $feature, $description, $imagePath);
                    //display text
                    UtilActivityLogging::saveUserActivityLog("User successfully updated product details.", ["product" => $updatedProduct->toArray()], config('logging.LOG_NAMES.USER_UPDATED_PRODUCT'));
                    Session::flash('success', "Product detail has been updated.");
                    $categories = $this->getCategories();
                    $products = $this->getProductsData();
                    return redirect()->route('products.index')->with(compact('products', 'categories')); //FIEX DUPLICATION OF IMAGE
                }
            }
            //check if img input is empty
            else
            {
                //check if there is for deletion of image
                if($forImageDeletion != "No-Image-Deletion")
                {
                    //check image to public path
                    if(File::exists(public_path($imgStoreInDB[$forImageDeletion])))
                    {
                        //all images from database, based of passed ID
                        $arrayIndex = $imgStoreInDB;
                        //delete image from public path
                        File::delete($imgStoreInDB[$forImageDeletion]);
                        //remove the image from database
                        unset($arrayIndex[$forImageDeletion]);
                        //all remaining image/s
                        $remainingArray = array_values($arrayIndex);
                        //save records
                        $updatedProduct = $this->updateProduct($productCode, $name, $price, $categoryId, $specification, $feature, $description, $remainingArray);
                        //display text
                        UtilActivityLogging::saveUserActivityLog("User successfully updated product details.", ["product" => $updatedProduct->toArray()], config('logging.LOG_NAMES.USER_UPDATED_PRODUCT'));
                        Session::flash('success', "Product detail has been updated.");
                        $categories = $this->getCategories();
                        $products = $this->getProductsData();
                        return redirect()->route('products.index')->with(compact('products', 'categories')); //FIEX DUPLICATION OF IMAGE
                    }
                }
                //check if there is  no deletion of image, just updating product details
                else
                {
                    //save records
                    $updatedProduct = $this->updateProduct($productCode, $name, $price, $categoryId, $specification, $feature, $description, $imgStoreInDB);
                    //display text
                    UtilActivityLogging::saveUserActivityLog("User successfully updated product details.", ["product" => $updatedProduct->toArray()], config('logging.LOG_NAMES.USER_UPDATED_PRODUCT'));
                    Session::flash('success', "Product detail has been updated.");
                    $categories = $this->getCategories();
                    $products = $this->getProductsData();
                    return redirect()->route('products.index')->with(compact('products', 'categories')); //FIEX DUPLICATION OF IMAGE
                }
            }
        }
        catch(Throwable $e)
        {
            //display return
            return redirect()->route('products.index');
        }
    }


    public function updateAjax(Request $request)
    {
        $id = $request->input('userID');
        $name = $request->input('product-name');
        $description = $request->input('description');
        $categoryId = $request->input('category-id');
        //$feature = $request->input('feature');
        //$specification = $request->input('specification');
        $productCode = $request->input('productCode');
        $forImageDeletion = $request->input('deleteImage');

        // search database by productCode value ang get images
        $imgStoreInDB = $this->getCurrentImagesOfProduct($productCode);
        $price = $this->getProductPriceViaProductCode($productCode);
        $feature = $this->getProductFeatureViaProductCode($productCode);
        $specification = $this->getProductSpecificationViaProductCode($productCode);

        if($forImageDeletion != null)
        {
            //all images from database, based of passed ID
            $arrayIndex = $imgStoreInDB;
            //delete image from public path
            File::delete($imgStoreInDB[$forImageDeletion]);
            //remove the image from database
            unset($arrayIndex[$forImageDeletion]);
            //all remaining image/s
            $remainingImages = array_values($arrayIndex);
            //save records
            $this->updateProduct($productCode, $name, $price, $categoryId, $specification, $feature, $description, $remainingImages);
            //display text
            Session::flash('success', "Product detail has been updated.");
            $categories = $this->getCategories();
            $products = $this->getProductsData();
            if($request->ajax())
        {
            //return $forImageDeletion;
            //return response()->json($testing);
            return response()->json([
                'status' => 200,
                'products' => $products,
                // 'userID' => $id,
                // 'name' => $name,
                // 'desc' => $description,
                // 'price' => $price,
                // 'categoryId' => $categoryId,
                // 'feature' => $feature,
                // 'spec' => $specification,
                // 'deleteImage' => $forImageDeletion,
                // 'products' => $productCode,
            ]);
        }
        }
    }
    /* -- do not delete.
    public function softDelete(Request $request, $removeImage)
    {
        try{
            //check if image is existing, if yes
            $productID = Product::where('product_code', $request->input('productId'))->first();
            $imageNumber = $request->input('deleteImage');
            if(File::exists(public_path($productID->image[$imageNumber])))
            {
                //array count
                $arrayIndex = $productID->image;
                //delete image from public path
                File::delete($productID->image[$imageNumber]);
                //remove the image from database
                unset($arrayIndex[$imageNumber]);
                $remainingArray = array_values($arrayIndex);
                $productID->image = $remainingArray;
                //save record
                $productID->save();
                //get the following data.
                $categories = $this->getCategories();
                $products = $this->getProductsData();
                //display message
                Session::flash('success', "Image has been remove succesfully.");
                //display return, if data duplication happened, just use redirect.
                //return view('products', compact('categories', 'products'));
                return redirect()->route('products')->with(compact('products', 'categories')); //FIEX DUPLICATION OF IMAGE
            }
            else{
                Session::flash('missing', "File does not exist.");
                return redirect()->route('products.index');
            }
        }catch(Throwable $e)
        {
            //display return
            return redirect()->route('products.index');
        }
    }
    */
}
