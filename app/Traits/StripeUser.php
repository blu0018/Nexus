<?php

namespace App\Traits;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

trait StripeUser 
{
 
    public $apiUrl = "https://api.stripe.com/v1/";
    public $apiKey = "sk_test_51OLTTsSImJlWzXcaQApudpYrcwcznzeQp6fY8Mgvoeai16bbHNMfcYN8zeueld5zIwA3Qd8dMNprkcDbPVDmnqt700s7Amq0I3";
    
    public function creteAccount($post){ 
        

        $data = [
            'type' => 'custom',
            'country' => 'IN',
            'capabilities[card_payments][requested]' => true,
            'capabilities[transfers][requested]' => true,
        ];

        
    
        $url = 'accounts?type=custom';
            $resp = $this->call('POST', $url, $data);
            return $resp;
            var_dump($resp);die;

    }

    public function call($method, $path, $data)
{
    $url = $this->apiUrl . $path;
    $ch = curl_init($url);

    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_USERPWD, $this->apiKey . ':');


    if ($method === 'POST') {
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'POST');
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
        curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/x-www-form-urlencoded']);
        // or use JSON
        // curl_setopt($curl, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);
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

    if (isset($result['error'])) {
        echo 'Stripe API error: ' . $result['error']['message'];
        return false;
    }

    return $result;
}


}
