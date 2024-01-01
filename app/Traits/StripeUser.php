<?php

namespace App\Traits;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use App\Models\Stripe;
use App\Models\BankAccount;

trait StripeUser 
{ 
    public $apiUrl = "https://api.stripe.com/v1/";
    public $contentType = "application/x-www-form-urlencoded";
    public $apiKey = "sk_test_51OLTTsSImJlWzXcaQApudpYrcwcznzeQp6fY8Mgvoeai16bbHNMfcYN8zeueld5zIwA3Qd8dMNprkcDbPVDmnqt700s7Amq0I3";

    public function stripeCustomer($user){
        $error = false; 
        $user_id = $user->id;

        $stripe = Stripe::where('user_id', $user_id)->first();
        $customer_id = !empty($stripe['id']) ? $stripe['customer_id'] : null;

        if (!$stripe) {
            $stripe = new Stripe();
            $stripe->user_id = $user_id;
        }

        $data = [
            'name' => $user->name,
            'email' => $user->email
        ];
        
        if(empty($customer_id))
            $resp = $this->call('POST', 'customers', $data);
        else 
            $resp = $this->call('POST', 'customers/'.$customer_id, $data);

        if(!empty($resp['id']))
            $customer_id = $resp['id'];
        else
            $error = $resp['error']['message'];
    
        if(!$error){
            $stripe->customer_id = $customer_id;
            $stripe->save();
        }
        return ['success' => !$error ? 'true' : 'false', 'error'=> $error, 'data' => !$error ? $stripe : null];
     
    }

    public function updateBank ($user, $post,$token=null)
    {
        $error = false; 
        $user_id = $user->id;
        $bank_token = $token;
        
        $bank = BankAccount::where(['user_id' => $user_id, 'bank_token' => $bank_token ])->first() ?: new BankAccount();
        
        $act_no = 000 .$post['account_number'];
        $a = 0 .$act_no;
        $account_number = 0 .$a;

        $data = [
            'country' => 'US',
            'currency' => 'usd',
            'account_holder_name' => $post['account_holder_name'],
            'account_holder_type' => 'individual',
            'routing_number' => $post['routing_number'],
            'account_number' => $account_number
        ];

        $resp = $this->call('POST', 'tokens', ['bank_account' => $data]);

        print_r($resp);die;
    }

    public function call($method, $path, $data)
    {
        $url = $this->apiUrl . $path;
        $ch = curl_init($url);

        $header = ['Content-Type: ' .$this->contentType];

        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
        curl_setopt($ch, CURLOPT_USERPWD, $this->apiKey . ":");
       
        if ($method === 'POST') {
            curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'POST');
            curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data));   
        }

        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        $response = curl_exec($ch);
        curl_close($ch);

        $result = json_decode($response, true);
        return $result;
    }


}
