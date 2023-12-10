<?php

namespace App\Http\Controllers;

use App\Models\Size;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\File;
use DB;
use Illuminate\Support\Facades\Auth;


class SizeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $size = Size::all();
        return view ('size.index',['sizes' => $size]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function manage_Size(Request $request)
    {
        return view ('size.manage');
    }

    public function updateSize(Request $request)
    {
        $id = $request->input('id');

        $rules = [
            'size' => 'required|unique:sizes,size,' . $id, // Ignore unique rule for the current record during update
            
        ];

        $request->validate($rules);

        DB::beginTransaction();
        try {
            $size = !empty($id) ? Size::find($id) : new Size;
            $size->size = $request->size;
            $size->save();

            DB::commit();
            return redirect()
                ->route('size.index')
                ->with('class', 'success')
                ->with('message', 'Size ' . (!empty($id) ? 'Updated' : 'Added') . ' successfully');
        } catch (\Throwable $th) {
            DB::rollback();
            return redirect()
                ->route('size.index')
                ->with('class', 'danger')
                ->with('message', "Error: ".$th->getMessage());
        }
    }

    public function editSize(Request $request, $id)
    {
        $size = Size::find($id);
        if(!empty($size)){
            return view ('size.manage',['size' => $size]);            
        }     
    }    

    
    public function deleteSize(Request $request, $id)
    {
        $size = Size::find($id);
        if(!empty($size)){
            $size->delete();
            return redirect()
                ->route('size.index')
                ->with('class', 'danger')
                ->with('message', 'Size Deleted Successfully');            
        }       
    }

    public function updateStatus(Request $request, $type, $id)
    {
        $size = Size::find($id);

        if (!$size) {
            return redirect()
                ->route('size.index')
                ->with('class', 'danger')
                ->with('message', 'Size not found');
        }

        $size->status = $type;
        $size->save();

        $action = $type == 1 ? 'Activated' : 'Deactivated';

        return redirect()
            ->route('size.index')
            ->with('class', $type == 1 ? 'success' : 'danger')
            ->with('message', 'Size ' . $action . ' successfully');
    }

}
