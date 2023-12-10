<?php

namespace App\Http\Controllers;

use App\Models\Coupon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\File;
use DB;
use Illuminate\Support\Facades\Auth;


class CouponController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $coupon = Coupon::all();
        return view ('coupon.index',['coupons' => $coupon]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function manage_Coupon(Request $request)
    {
        return view ('coupon.manage');
    }

    public function updateCoupon(Request $request)
    {
        $id = $request->input('id');

        $rules = [
            'title' => 'required|string',
            'code' => 'required|unique:coupons,code,' . $id, // Ignore unique rule for the current record during update
            'value' => 'required',
        ];

        $request->validate($rules);

        DB::beginTransaction();
        try {
            $coupon = !empty($id) ? Coupon::find($id) : new Coupon;
            $coupon->title = $request->title;
            $coupon->code  = $request->code;
            $coupon->value = $request->value;
            $coupon->save();

            DB::commit();
            return redirect()
                ->route('coupon.index')
                ->with('class', 'success')
                ->with('message', 'Coupon ' . (!empty($id) ? 'Updated' : 'Added') . ' successfully');
        } catch (\Throwable $th) {
            DB::rollback();
            return redirect()
                ->route('coupon.index')
                ->with('class', 'danger')
                ->with('message', "Error: ".$th->getMessage());
        }
    }

    public function editCoupon(Request $request, $id)
    {
        $coupon = Coupon::find($id);
        if(!empty($coupon)){
            return view ('coupon.manage',['coupon' => $coupon]);            
        }     
    }    

    
    public function deleteCoupon(Request $request, $id)
    {
        $coupon = Coupon::find($id);
        if(!empty($coupon)){
            $coupon->delete();
            return redirect()
                ->route('coupon.index')
                ->with('class', 'danger')
                ->with('message', 'Coupon Deleted Successfully');            
        }       
    }

    public function updateStatus(Request $request, $type, $id)
    {
        $coupon = Coupon::find($id);

        if (!$coupon) {
            return redirect()
                ->route('coupon.index')
                ->with('class', 'danger')
                ->with('message', 'Coupon not found');
        }

        $coupon->status = $type;
        $coupon->save();

        $action = $type == 1 ? 'Activated' : 'Deactivated';

        return redirect()
            ->route('coupon.index')
            ->with('class', $type == 1 ? 'success' : 'danger')
            ->with('message', 'Coupon ' . $action . ' successfully');
    }

}
