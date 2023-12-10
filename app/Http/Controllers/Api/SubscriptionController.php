<?php

namespace App\Http\Controllers\Api;

use App\Models\Subscription;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use App\Models\UserSubscription;
use Carbon\Carbon;

class SubscriptionController extends Controller
{
    public function calculation(Request $request)
    {
        $data = $request->only('price', 'discount');
        $price = $data['price'];
        $discount = $data['discount'];

        if (is_numeric($price) && is_numeric($discount)) {
            $discount_price = $price * ($discount / 100);
            $orginal_price = $price - $discount_price;
            return response()->json(['discounted_price' => $orginal_price]);
        } else {
            return response()->json(['error' => 'Invalid input. Please provide numeric values for price and discount.'], 400);
        }
    }

    public function getSubscriptionlist (Request $request)
    {
        $user_id = auth()->user()->id;

        $subscription = Subscription::where('status',1)->get();

        if($subscription->isNotEmpty())
            return response()->json(['error'=>false, 'data' => $subscription]);
        else
            return response()->json(['error'=>'no subscription found']);
    }

    public function subscriptionPurchase (Request $request)
    {
        $data = $request->only('subscription_id');
        $rule = [
            'subscription_id' => 'required',
        ];

        $user_id = auth()->user()->id;

        $validator = validator::make($data, $rule);
        if ($validator->fails()) 
            return response()->json($validator->errors(), 422);

        $subscription = Subscription::where('id',$data)->first();

        if(!empty($subscription)){
            list($data,$error) = $this->createSubscription($subscription,$user_id);
            if(!$error)
                return response()->json(['error'=>false, 'data' => $data]);
            else 
                return response()->json(['error'=> $error]);

        } else{
            return response()->json(['error'=>'no subscription found']);
        }
    }


    public function getUserSubscription(Request $request)
    {   
        try {
            $user_id = auth()->user()->id;
            $subscription = UserSubscription::with('subscription')->where('user_id', $user_id)->get();

            if ($subscription->isNotEmpty()) {
                foreach($subscription as $sub){
                    $endDate = strtotime($sub->expiration_date);
                    $currentTime = strtotime(date('Y-m-d'));
                    $secondsLeft = $endDate - $currentTime;
                    $daysLeft = (int)($secondsLeft / (60 * 60 * 24));
                    $sub->days_left = $daysLeft;   
                }
                return response()->json(['error' => false, 'data' => $subscription]);
            } else {
                return response()->json(['error' => 'no subscription found']);
            }
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getmessage()]);
        }
    }

    private function createSubscription($subscription,$user_id)
    {  
        $error = false;
        $transaction_id = Str::random(10);
        $subscription_id =  $subscription['id'];
        $dateTime = date("Y-m-d");
        $start_date = $payment_date = $dateTime;

        $period = ($subscription['period'] == 'weekly') ? 'week' : (($subscription['period'] == 'monthly') ? 'month' : 'year');

        if($subscription['free_trial'] == 1 && $subscription['free_trial_days'] > 0){
            $free_trail_days = $subscription['free_trial_days'];

            $payment_date = date('Y-m-d', strtotime(" $free_trail_days day"));
            $expiration_date = date('Y-m-d', strtotime("+1 $payment_date + $free_trail_days day"));
            $next_payment_date = date('Y-m-d', strtotime("$expiration_date +1 day"));
        } else {

            $expiration_date = date('Y-m-d', strtotime("+1 $period -1 day"));
            $next_payment_date = date('Y-m-d', strtotime("$expiration_date +1 day"));
        }

        $sub = UserSubscription::where('user_id', $user_id)->first();
        if( !$sub )
            $sub = new UserSubscription();

        $sub->user_id   = $user_id;
        $sub->subscription_id   = $subscription_id;
        $sub->transaction_id   = $transaction_id;
        $sub->start_date   = $start_date;
        $sub->payment_date   = $payment_date;
        $sub->expiration_date   = $expiration_date;
        $sub->next_payment_date   = $next_payment_date;
        $sub->save();

        if(!$sub->id){
            $error = "Unable to create Subscription";
        }
        return [$sub,$error] ;   
    }
}
