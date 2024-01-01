<?php

namespace App\Traits;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

trait Plaid 
{
 
    public $apiUrl = "https://sandbox.plaid.com/";
    public $plaidClientId = "6578896961c5c4001c0d1105"; 
    public $plaidSecret = "342c908961f6e7da901d188b8f760a"; 
    public $uniqueUserId = "Test@"; 
    public $product = "transactions"; 
    public $redirectUri = "https://giant-nights-yell.loca.lt"; 

    public $contentType = "application/json";
   
    public function token_exchange($data)
    {
        $error = false; 
        $public_token = $data['public_token'];
        $account_id = $data['account_id'];
        
        $data = array(
            "client_id" => $this->plaidClientId,
            "secret" => $this->plaidSecret,
            "public_token" => $public_token
        );
        $resp = $this->call('POST', 'item/public_token/exchange', $data);
        
        if(!empty(@$resp['access_token'])){
            $access_token = $resp['access_token'];

            $data = array(
                "client_id" => $this->plaidClientId,
                "secret" => $this->plaidSecret,
                "access_token" => $access_token,
                "account_id" => $account_id,
            );

            $resp = $this->call('POST', 'processor/stripe/bank_account_token/create', $data);
            var_dump($resp);die;
    
        } else {
            $error = $resp['error_message'];
        }
        return [@$access_token, $error];
     
    }
    
    public function likn_token(){
        $error = false; 

        $uid = $this->uniqueUserId . rand(111,999);
        
        $data = array(
            "client_id" => $this->plaidClientId,
            "secret" => $this->plaidSecret,
            "client_name" => "Plaid Test App",
            "user" => array("client_user_id" => $uid),
            "products" => array($this->product),
            "country_codes" => array("US"),
            "language" => "en",
           // "webhook" => $webhook,
            "redirect_uri" => $this->redirectUri
        );
       
    
        $resp = $this->call('POST', 'link/token/create', $data);
        
        if(!empty(@$resp['link_token'])){
            $link_token = $resp['link_token'];

        } else {
            $error = $resp['error_message'];
        }
        return [@$link_token, $error];
     
    }

    public function call($method, $path, $data)
    {
        $url = $this->apiUrl . $path;
        $ch = curl_init($url);

        $header = ['Content-Type: ' .$this->contentType];

        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
    

       
        if ($method === 'POST') {
            curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'POST');
            curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
            
        }

        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        $response = curl_exec($ch);

        // Check for cURL errors
        if (curl_errno($ch)) {
            echo 'cURL error: ' . curl_error($ch);
                return false;
        }

        curl_close($ch);
        $result = json_decode($response, true);
        return $result;
    }


}
