<?php

namespace App\Traits;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

trait shopify 
{

    public $contentType = "application/json";

    public function getProduct(){
       return $this->shopifyCall('GET');
    }

    public function addProduct($data)
    {
        $results = [];

        $response = $this->shopifyCall('GET');
        echo "<pre>"; print_r($response);die;

        
        /*
        foreach ($data['products'] as $product) {
            $productData = [
                "product" => [
                    "title" => $product['title'],
                    "body_html" => $product['body_html'],
                    "vendor" => $product['vendor'],
                    // Add more product fields as needed
                    'images' => [],
                ]
            ];

            if (isset($product['images']) && is_array($product['images'])) {
                foreach ($product['images'] as $pImage) {
                    // Add each image to the 'images' array
                    $productData['product']['images'][] = [
                        'src' => $pImage['src']
                    ];
                }
            }

            $results[] = $productData;
        }
            foreach ($results as $productData) {
            $response = $this->shopifyCall('POST', $productData);

            // Handle the response as needed
            echo "<pre>";
            print_r($response);
        }
        */
    }


    public function shopifyCall($method , $data = [])
    {    
        // Replace these with your Shopify store's credentials and the API endpoint
        $shopifyStore = 'dropshipper-0018.myshopify.com';
        $apiUrl = 'https://' . $shopifyStore . '/admin/api/2022-01/products/6872205852747.json';

        $accessToken = "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";
        $headers = [
            'X-Shopify-Access-Token: ' . $accessToken,
            'Content-Type: application/json'
        ];

        $curl = curl_init($apiUrl);
        curl_setopt($curl, CURLOPT_URL, $apiUrl);
        curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);

        if ($method === 'POST') {
            curl_setopt($curl, CURLOPT_CUSTOMREQUEST, 'POST');
            curl_setopt($curl, CURLOPT_POSTFIELDS, json_encode($data));
        }
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
        $response = curl_exec($curl);
        // Check for cURL errors
        if (curl_errno($curl)) {
            echo 'cURL error: ' . curl_error($curl);
        }
        curl_close($curl);
        $result = json_decode($response, true);
        return $result;
    }

}
