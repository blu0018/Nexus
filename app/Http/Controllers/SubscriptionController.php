<?php

namespace App\Http\Controllers;

use App\Models\Subscription;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\File;
use DB;

class SubscriptionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $subscription = Subscription::all();
        return view ('subscription.index',['subscriptions' => $subscription]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function manage_subscription(Request $request)
    {
        return view ('subscription.manage');
    }

    public function updateSubscription(Request $request)
    {
        $id = $request->input('id');

        $rules = [
            'name' => 'required|string',
            'period' => 'required|string',
            'price' => 'required|string',
            
        ];

        if (empty($id))
            $rules['image'] = 'required|image|mimes:jpeg,png,jpg,gif|max:2048';

        $request->validate($rules);

        DB::beginTransaction();

        try {
            $subscription = !empty($id) ? Subscription::find($id) : new Subscription;
            $old_image = !empty($id) ? $subscription->image : '';
            $subscription->name = $request->name;
            $subscription->free_trial = $request->free_trial;
            $subscription->free_trial_days = $request->free_trial_days;
            $subscription->period = strtolower($request->period);
            $subscription->price = $request->price;

            if ($request->hasFile('image')) {
                $image = $this->fileUpload($request->image, '/Subscription', $old_image);
                $subscription->image = $image;
            } else {
                if (!empty($id) && !$request->hasFile('image')) {
                    $subscription->image = $old_image;
                }
            }

            $subscription->save();

            DB::commit();
            return redirect()
                ->route('subscription.index')
                ->with('class', 'success')
                ->with('message', 'Subscription ' . (!empty($id) ? 'Updated' : 'Added') . ' successfully');
        } catch (\Throwable $th) {
            DB::rollback();
            return redirect()
                ->route('subscription.index')
                ->with('class', 'danger')
                ->with('message', "Error: ".$th->getMessage());
        }
    }
  
    public function editSubscription(Request $request, $id)
    {
        $subscription = Subscription::find($id);
        if(!empty($subscription)){
            return view ('subscription.manage',['subscription' => $subscription]);            
        }     
    }    

    
    public function deleteSubscription(Request $request, $id)
    {
        $subscription = Subscription::find($id);
        if(!empty($subscription)){
            $old_image = $subscription->image;
            $this->deleteImage('Subscription', $old_image);
            $subscription->delete();
            return redirect()
                ->route('subscription.index')
                ->with('class', 'danger')
                ->with('message', 'Subscription Deleted Successfully');            
        }       
    }

    public function updateStatus(Request $request, $type, $id)
    {
        $subscription = Subscription::find($id);

        if (!$subscription) {
            return redirect()
                ->route('subscription.index')
                ->with('class', 'danger')
                ->with('message', 'Subscription not found');
        }

        $subscription->status = $type;
        $subscription->save();

        $action = $type == 1 ? 'Activated' : 'Deactivated';

        return redirect()
            ->route('subscription.index')
            ->with('class', $type == 1 ? 'success' : 'danger')
            ->with('message', 'Subscription ' . $action . ' successfully');
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
