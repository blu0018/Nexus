<?php

namespace App\Http\Controllers\Api;

use App\Models\Stripe;
use App\Traits\StripeUser;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class StripeController extends Controller
{
    /**
     * Display a listing of the resource.
     */

    use StripeUser;

    public function createUpdateCustomer(Request $request)
    {
        $data = $request->only('email');
        $user = auth()->user();
        $user_id = $user->id;
        $error = false;

            
        try {
            $customer = $this->stripeCustomer($user);
            
            return $customer;
                
        } catch (\Throwable $th) {
            $error = $th->getMessage();
        }

        return [
            'success' => !$error ? true : false,
            'data' => !$error ? $stripe : null,
            'error' => $error, 
        ];
    } 

    public function createUpdateBank(Request $request)
    {
        $data = $request->only('account_holder_name','routing_number','account_number');
        $user = auth()->user();
        $user_id = $user->id;
        $error = false;

            
        try {
            $customer = $this->updateBank($user,$data);
            
            return $customer;
                
        } catch (\Throwable $th) {
            $error = $th->getMessage();
        }

        return [
            'success' => !$error ? true : false,
            'data' => !$error ? $stripe : null,
            'error' => $error, 
        ];
    }

}

