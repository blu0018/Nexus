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

    public function createAccount(Request $request)
    {
        $data = $request->only('email');
        $user_id = auth()->user()->id;

        $account = $this->creteAccount($data);
        return $account;
        var_dump($data,$user_id);
    }

}
