<?php

namespace App\Http\Controllers;

use App\Models\Color;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\File;
use DB;
use Illuminate\Support\Facades\Auth;


class ColorController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $color = Color::all();
        return view ('color.index',['colors' => $color]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function manage_Color(Request $request)
    {
        return view ('color.manage');
    }

    public function updateColor(Request $request)
    {
        $id = $request->input('id');

        $rules = [
            'color' => 'required|unique:colors,color,' . $id, // Ignore unique rule for the current record during update
            
        ];

        $request->validate($rules);

        DB::beginTransaction();
        try {
            $color = !empty($id) ? Color::find($id) : new Color;
            $color->color = $request->color;
            $color->save();

            DB::commit();
            return redirect()
                ->route('color.index')
                ->with('class', 'success')
                ->with('message', 'Color ' . (!empty($id) ? 'Updated' : 'Added') . ' successfully');
        } catch (\Throwable $th) {
            DB::rollback();
            return redirect()
                ->route('color.index')
                ->with('class', 'danger')
                ->with('message', "Error: ".$th->getMessage());
        }
    }

    public function editColor(Request $request, $id)
    {
        $color = Color::find($id);
        if(!empty($color)){
            return view ('color.manage',['color' => $color]);            
        }     
    }    

    
    public function deleteColor(Request $request, $id)
    {
        $color = Color::find($id);
        if(!empty($color)){
            $color->delete();
            return redirect()
                ->route('color.index')
                ->with('class', 'danger')
                ->with('message', 'Color Deleted Successfully');            
        }       
    }

    public function updateStatus(Request $request, $type, $id)
    {
        $color = Color::find($id);

        if (!$color) {
            return redirect()
                ->route('color.index')
                ->with('class', 'danger')
                ->with('message', 'Color not found');
        }

        $color->status = $type;
        $color->save();

        $action = $type == 1 ? 'Activated' : 'Deactivated';

        return redirect()
            ->route('color.index')
            ->with('class', $type == 1 ? 'success' : 'danger')
            ->with('message', 'Color ' . $action . ' successfully');
    }

}
