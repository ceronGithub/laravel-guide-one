<?php 
namespace App\Traits\DB; 

use App\Models\Category;
use App\Requests\Category\RegisterCategoryRequest;

use App\Http\Controllers\UI\categoryController;

    trait CategoryTable
    {
        function CategoryList()
        {
            $categoryList = Category::paginate(10);  
            return $categoryList;      
        }
        function createCategory(RegisterCategoryRequest $request)
        {
            //create new store
            $category = Category::create($request->all());
            //return newly created store
            return $category;
        }

        function updateCategory($id, $name, $desc)
        {
            $update = Category::where('id', $id)->first();
            $update->name = $name != null ? $name : $update->name;
            $update->desc = $desc != null ? $desc : $update->desc;
            $update->save();
            return $update;
        }

        public function deleteCategory($id)
        {
            $delete = Category::where("id", $id)
                ->delete();
            return $delete;
        }        
    }
?>