<?php

namespace App\Traits\DB;

use App\Models\Product;
use App\Models\Category;
use App\Requests\Product\RegisterProductRequest;

trait ProductTable
{
    public function getProductsData($categoryId = null)
    {
        if($categoryId == 0)
        {
            return Product::all();
        }
        else
        {
            return Product::where('category_id', $categoryId)->get();
        }
    }

    public function getProductPriceViaProductCode($code = null)
    {
        if($code == 0)
        {
            return Product::all();
        }
        else
        {
            return Product::where('product_code', $code)->first(['price'])->price;
        }
    }
    public function getProductFeatureViaProductCode($code = null)
    {
        if($code == 0)
        {
            return Product::all();
        }
        else
        {
            return Product::where('product_code', $code)->first(['feature'])->feature;
        }
    }
    public function getProductSpecificationViaProductCode($code = null)
    {
        if($code == 0)
        {
            return Product::all();
        }
        else
        {
            return Product::where('product_code', $code)->first(['specification'])->specification;
        }
    }

    public function DuplicateIDChecker($id)
    {
        return Product::where('product_code', $id)->exists();
    }

    public function RegisterProduct($productName, $productDesc, $productPrice, $productCategoryId, $productFeature, $productSpecification, $productProductcode, $productImage)
    {
        $product = new Product();
        $product->image = $productImage;
        $product->name = $productName;
        $product->desc = $productDesc;
        $product->price = $productPrice;
        $product->category_id = $productCategoryId;
        $product->feature = $productFeature;
        $product->specification = $productSpecification;
        $product->product_code = $productProductcode;
        $product->save();
        return $product;
    }

    public function getCurrentImagesOfProduct($productCode)
    {
        return Product::where('product_code',$productCode)->first(['image'])->image;
    }

    public function getProductName($productCode)
    {
        return Product::where('product_code',$productCode)->first(['name'])->name;
    }


    public function updateProduct($id, $productName, $productPrice, $productCategoryID, $productSpecs, $productFeature, $productDesc, $productImg)
    {
        $forUpdate = Product::where('product_code', $id)->first();
        $forUpdate->name = $productName;
        $forUpdate->price = $productPrice;
        $forUpdate->category_id = $productCategoryID;
        $forUpdate->specification = $productSpecs;
        $forUpdate->feature = $productFeature;
        $forUpdate->desc = $productDesc;
        $forUpdate->image = $productImg;
        $forUpdate->save();
        return $forUpdate;
    }


    public function getProductData($id)
    {
        return Product::where('id', $id)->first();
    }

    public function getCategories()
    {
        return Category::orderBy(Category::COLUMN_NAME, 'asc')->get();
    }

    function addScreenshotPath(array $array, String $screenshotPath): array
    {
        $array = array_merge($array, ['image_path' => $screenshotPath,]);
        $id_image_hash = hash('sha256', $screenshotPath);
        $array = array_merge($array, ['id_image_hash' => $id_image_hash,]);
        return $array;
    }

    public function base64_to_jpeg($base64_string, $output_file)
    {
        $ifp = fopen($output_file, "wb");
        fwrite($ifp, base64_decode($base64_string));
        fclose($ifp);
        return ($output_file);
    }
}
