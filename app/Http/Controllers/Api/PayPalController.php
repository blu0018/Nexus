<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class PayPalController extends Controller
{
    public $apiUrl = "https://api-m.sandbox.paypal.com/v1/";
    public $client_id = "AdvGS6Myj0ADA6QFCzdMMkROUe9-Ezw8_j36qP9JWa_JztA38tf6XtNhgjnurNGb6frhvvtglRKwurWn";
    public $client_secret = "EJNAtJkeB4dktMWOjMr2Rw38BknfbnCrrnlVACUMGus62ENwCskwOq8ABj51hMA18XA0RKWmmjK6YHBu";
    public $access_token;
    public $error = false;

    public function __construct()
    {
        $credentials = base64_encode($this->client_id . ':' . $this->client_secret);

        $headers = [
            'Content-Type: application/x-www-form-urlencoded',
            'Authorization: Basic ' . $credentials,
        ];

        $data = [
            'grant_type' => 'client_credentials',
        ];

        $resp = $this->call('POST', 'oauth2/token', $headers, $data);
        if(@$resp['error'])
            $this->error = $resp['error'];
        
        $this->access_token = isset($resp['access_token']) ? $resp['access_token'] : null;
        
    }

    public function createPlan()
    {
        $headers = [
            'Authorization: Bearer ' . $this->access_token,
            'Content-Type: application/json',
            'Accept: application/json',
           // 'PayPal-Request-Id: PLAN-18062019-001',
        ];

        $data = [
            "product_id" => "PROD-XXCD1234QWER65782",
            "name" => "Video Streaming Service Plan",
            "description" => "Video Streaming Service basic plan",
            "status" => "ACTIVE",
            "billing_cycles" => [
                [
                    "frequency" => [
                        "interval_unit" => "MONTH",
                        "interval_count" => 1
                    ],
                    "tenure_type" => "TRIAL",
                    "sequence" => 1,
                    "total_cycles" => 2,
                    "pricing_scheme" => [
                        "fixed_price" => [
                            "value" => "3",
                            "currency_code" => "USD"
                        ]
                    ]
                ],
                [
                    "frequency" => [
                        "interval_unit" => "MONTH",
                        "interval_count" => 1
                    ],
                    "tenure_type" => "TRIAL",
                    "sequence" => 2,
                    "total_cycles" => 3,
                    "pricing_scheme" => [
                        "fixed_price" => [
                            "value" => "6",
                            "currency_code" => "USD"
                        ]
                    ]
                ],
                [
                    "frequency" => [
                        "interval_unit" => "MONTH",
                        "interval_count" => 1
                    ],
                    "tenure_type" => "REGULAR",
                    "sequence" => 3,
                    "total_cycles" => 12,
                    "pricing_scheme" => [
                        "fixed_price" => [
                            "value" => "10",
                            "currency_code" => "USD"
                        ]
                    ]
                ],
            ],
            "payment_preferences" => [
                "auto_bill_outstanding" => true,
                "setup_fee" => [
                    "value" => "10",
                    "currency_code" => "USD"
                ],
                "setup_fee_failure_action" => "CONTINUE",
                "payment_failure_threshold" => 3
            ],
            "taxes" => [
                "percentage" => "10",
                "inclusive" => false
            ]
        ];

        $resp = $this->call('POST', 'billing/plans', $headers, $data);

        print_r($resp); die('d');// You can remove this line or modify it as needed
    }


    public function createSubscription()
    {
        $headers = [
            'Authorization: Bearer ' . $this->access_token,
            'Content-Type: application/json',
            'Accept: application/json',
            'PayPal-Request-Id: SUBSCRIPTION-21092019-001',
        ];

        $data = [
            "plan_id" => "P-5ML4271244454362WXNWU5NQ",
            "start_time" => "2023-12-05T00:00:00Z",
            "quantity" => "20",
            "shipping_amount" => [
                "currency_code" => "USD",
                "value" => "10.00",
            ],
            "subscriber" => [
                "name" => [
                    "given_name" => "John",
                    "surname" => "Doe",
                ],
                "email_address" => "customer@example.com",
                "shipping_address" => [
                    "name" => [
                        "full_name" => "John Doe",
                    ],
                    "address" => [
                        "address_line_1" => "2211 N First Street",
                        "address_line_2" => "Building 17",
                        "admin_area_2" => "San Jose",
                        "admin_area_1" => "CA",
                        "postal_code" => "95131",
                        "country_code" => "US",
                    ],
                ],
            ],
            "application_context" => [
                "brand_name" => "walmart",
                "locale" => "en-US",
                "shipping_preference" => "SET_PROVIDED_ADDRESS",
                "user_action" => "SUBSCRIBE_NOW",
                "payment_method" => [
                    "payer_selected" => "PAYPAL",
                    "payee_preferred" => "IMMEDIATE_PAYMENT_REQUIRED",
                ],
                "return_url" => "https://example.com/returnUrl",
                "cancel_url" => "https://example.com/cancelUrl",
            ],
        ];

        $resp = $this->call('POST', 'billing/subscriptions', $headers, $data);

        print_r($resp); // You can remove this line or modify it as needed
    }

    public function call($method, $endpoint, $headers = [], $data = [])
    {
        $url = $this->constructApiUrl($endpoint);
        $ch = curl_init($url);

        if ($method === 'POST') {
            curl_setopt($ch, CURLOPT_POST, true);
            if (strpos($endpoint, 'oauth2/token') === false) {
                curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
            } else {
                curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data));
            }
        }
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

        $response = curl_exec($ch);

        if (curl_errno($ch)) {
            echo 'Error: ' . curl_error($ch);
        }

        curl_close($ch);

        return json_decode($response, true);
    }

    private function constructApiUrl($endpoint)
    {
        return $this->apiUrl . $endpoint;
    }
}
