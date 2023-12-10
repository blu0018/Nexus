<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\File;
use DB;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    { 
        //var_dump(auth::user);die;
        $product = Product::all();
        return view ('product.index',['products' => $product]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function manage_product(Request $request)
    {   
        $category = Category::where('status','1')->get();
        return view ('product.manage',[ 'categories' => $category ]);
    }

    public function updateProduct(Request $request)
    {
        $id = $request->input('id');

        $rules = [
            'name' => 'required|string',
            'category_id' => 'required',
            'slug' => 'required|unique:products,slug,' . $id, // Ignore unique rule for the current record during update
            
        ];

        if (empty($id))
            $rules['image'] = 'required|image|mimes:jpeg,png,jpg,gif|max:2048';

        $request->validate($rules);

        DB::beginTransaction();

        try {
            $product = !empty($id) ? Product::find($id) : new Product;
            $old_image = !empty($id) ? $product->image : '';

            $product->name = $request->name;
            $product->slug = $request->slug;
            $product->category_id = $request->category_id;
            $product->brand = $request->brand;
            $product->model = $request->model;
            $product->short_desc = $request->short_desc;
            $product->desc = $request->desc;
            $product->keywords = $request->keywords;
            $product->technical_specification = $request->technical_specification;
            $product->uses = $request->uses;
            $product->warranty = $request->warranty;

            if ($request->hasFile('image')) {
                $image = $this->fileUpload($request->image, '/Product', $old_image);
                $product->image = $image;
            } else {
                if (!empty($id) && !$request->hasFile('image')) {
                    $product->image = $old_image;
                }
            }
            $product->save();

            DB::commit();
            return redirect()->route('product.index')->with('class', 'success')->with('message', 'Product ' . (!empty($id) ? 'Updated' : 'Added') . ' successfully');
        } catch (\Throwable $th) {
            DB::rollback();
            return redirect()->route('product.index')->with('class', 'danger')->with('message', "Error: ".$th->getMessage());
        }
    }
  
    public function editProduct(Request $request, $id)
    {
        $product = Product::find($id);
        $category = Category::where('status','1')->get();
        if(!empty($product)){
            return view ('product.manage',['product' => $product, 'categories' => @$category]);            
        }     
    }    

    public function deleteProduct(Request $request, $id)
    {
        $product = Product::find($id);
        if(!empty($product)){
            $old_image = $product->image;
            $this->deleteImage('Product', $old_image);
            $product->delete();
            return redirect()->route('product.index')->with('class', 'danger')->with('message', 'product Deleted Successfully');            
        }       
    }

    public function updateStatus(Request $request, $type, $id)
    {
        $product = Product::find($id);

        if (!$product) {
            return redirect()->route('product.index')->with('class', 'danger')->with('message', 'Product not found');
        }

        $product->status = $type;
        $product->save();

        $action = $type == 1 ? 'Activated' : 'Deactivated';

        return redirect()->route('product.index')->with('class', $type == 1 ? 'success' : 'danger')->with('message', 'Product ' . $action . ' successfully');
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
