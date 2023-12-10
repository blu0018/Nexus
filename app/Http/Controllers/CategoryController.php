<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\File;
use DB;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $category = Category::all();
        return view ('category.index',['categories' => $category]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function manage_category(Request $request)
    {
        return view ('category.manage');
    }

    public function updateCategory(Request $request)
    {
        $id = $request->input('id');

        $rules = [
            'name' => 'required|string',
            'slug' => 'required|unique:category,slug,' . $id, // Ignore unique rule for the current record during update
            'description' => 'required',
        ];

        if (empty($id))
            $rules['image'] = 'required|image|mimes:jpeg,png,jpg,gif|max:2048';

        $request->validate($rules);

        DB::beginTransaction();

        try {
            $category = !empty($id) ? Category::find($id) : new Category;
            $old_image = !empty($id) ? $category->image : '';

            $category->name = $request->name;
            $category->slug = $request->slug;
            $category->description = $request->description;

            if ($request->hasFile('image')) {
                $image = $this->fileUpload($request->image, '/Category', $old_image);
                $category->image = $image;
            } else {
                if (!empty($id) && !$request->hasFile('image')) {
                    $category->image = $old_image;
                }
            }

            $category->save();

            DB::commit();
            return redirect()
                ->route('category.index')
                ->with('class', 'success')
                ->with('message', 'Category ' . (!empty($id) ? 'Updated' : 'Added') . ' successfully');
        } catch (\Throwable $th) {
            DB::rollback();
            return redirect()
                ->route('category.index')
                ->with('class', 'danger')
                ->with('message', "Error: ".$th->getMessage());
        }
    }
  
    public function editCategory(Request $request, $id)
    {
        $category = Category::find($id);
        if(!empty($category)){
            return view ('category.manage',['category' => $category]);            
        }     
    }    

    
    public function deleteCategory(Request $request, $id)
    {
        $category = Category::find($id);
        if(!empty($category)){
            $old_image = $category->image;
            $this->deleteImage('Category', $old_image);
            $category->delete();
            return redirect()
                ->route('category.index')
                ->with('class', 'danger')
                ->with('message', 'Category Deleted Successfully');            
        }       
    }

    public function updateStatus(Request $request, $type, $id)
    {
        $category = Category::find($id);

        if (!$category) {
            return redirect()
                ->route('category.index')
                ->with('class', 'danger')
                ->with('message', 'Category not found');
        }

        $category->status = $type;
        $category->save();

        $action = $type == 1 ? 'Activated' : 'Deactivated';

        return redirect()
            ->route('category.index')
            ->with('class', $type == 1 ? 'success' : 'danger')
            ->with('message', 'Category ' . $action . ' successfully');
    }

    private function fileUpload($image, $path,$old_image=null)
    {
        try {
            $this->deleteImage($path, $old_image);
            $path = public_path($path);
            if (!is_dir($path))
                mkdir($path, 755, true);

            $file_name = time() . '.' . $image->extension();
            $image->move($path, $file_name);
            return $file_name;
        } catch (\Throwable $th) {
            return $th->getMessage();
        }                

    }

    private function deleteImage($path, $old_image)
    {       
        $image_path = public_path($path).'/'.$old_image;
        if(File::exists($image_path))
            File::delete($image_path);
            
    }

}
