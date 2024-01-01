<?php

namespace App\Http\Controllers;

use App\Models\Admin;
use Illuminate\Http\Request;
use App\Traits\shopify;
use App\Traits\Plaid;
use Illuminate\Support\Facades\Hash;
use Laravel\Socialite\Facades\Socialite;
use App\Models\User;
use App\Models\Category;
use Illuminate\Support\Facades\Session;


class AdminController extends Controller
{
    use shopify;
    /**
     * Display a listing of the resource.
     */

     public $accessToken;


     public function fancyBox() {

        $category = Category::all();
        
        return view ('fancy', ['category' => $category]);

     }

     public function addBank()
     {

        list($link_token,$error )= $this->likn_token();

        return view('bank',compact('link_token'));
     }

     public function tokenExchange(Request $request)
     {
        $data = $request->only('public_token','account_id');


        list($exchange,$error ) = $this->token_exchange($data);

        var_dump($exchange);die;

     }

     public function import(Request $request)
     {
        
        $method = $request->get('method');
       

        if($method == 'prepare'){
            $getData = $this->getProduct();

            if ($getData) {
            $path = storage_path('shopify_data');

            $fileName = 'shopify_data.json';     
           
            
            if (!is_dir($path))
                mkdir($path, 0755, true);

            $filePath = $path . '/' . $fileName;
            if(file_exists($filePath))
            unlink($filePath);
        
            @file_put_contents($filePath, json_encode($getData));

            } else {
                // Handle the case where the Shopify API call did not return data
                return 'Error: No data received from Shopify.';
            }
        }

        if ($method == 'import') { 
            $path = storage_path('shopify_data'); // Adjust the path as needed
            $fileName = 'shopify_data.json';
            $filePath = $path . '/' . $fileName;
        
            if (file_exists($filePath)) { 
                $file = @file_get_contents($filePath);      
                $data = json_decode($file, true);

                $this->addProduct($data);
    
            } else {
                return ['error' => 'File not found'];
            }
        }
     }
     
    public function index(Request $request)
    {   
        if(!$request->session()->has('admin_login')){
            return view ('admin.login'); 
        }else {
            return view ('admin.dashboard');
        }
    }

    public function auth(Request $request)
    {
        $email = $request->input('email');
        $password = $request->input('password');
        $result = Admin::where('email', $email)->first();

        if($result && Hash::check($password, $result->password)){
            $request->session()->put('admin_login',true);
            $request->session()->put('admin_id',$result->id);
            return redirect ('admin/dashboard');
        } else{
            $request->session()->flash('error','Please enter valid login details!');
            return redirect('login');
        }
        
    }

    public function hashPasssword(Request $request){
        $password = $request->get('password');
        $model = Admin::find(1);
        $model->password = Hash::make($password);
        $model->save();
        return response()->json(['success'=> 'true','message'=>'Password Encrypted Successfully']);
    }

    public function logout(Request $request){
        session()->forget('admin_login');
        session()->forget('admin_id');
        session()->flash('error', 'Logout Sucessfully');
        return redirect('login');
    }

    public function dashboard(Request $request)
    { 
        return view ('admin.dashboard');
    }
/*
    public function redirectToGoogle()
    {
        return Socialite::driver('google')->redirect();
    }

    public function handleGoogleCallback(Request $request)
    {
        try {
            $google_user = Socialite::driver('google')->user();
            $user = User::where('email', $google_user->email)->first();
           
            if(!$user)
            $user = new User();

            $user->name  = $google_user->name;
            $user->email = $google_user->email;
            $user->password = Hash::make($google_user->email);
            $user->photo  = $google_user->avatar;
            $user->save();
            $request->session()->put('admin_login',true);
            $request->session()->put('admin_id',$user->id);
            return redirect()->route('admin.dashboard');

         //   return view ('admin.dashboard');

        } catch (\Exception $e) {
            return redirect('/login')->with('error', $e->getmessage());
        }
    }
    */

    public function redirectToGoogle()
    {
        $state = bin2hex(random_bytes(16));
        session(['google_state' => $state]);

        $params = [
            'response_type' => 'code',
            'client_id' => config('services.google.client_id'),
            'redirect_uri' => config('services.google.redirect'),
            'scope' => 'openid profile email', // Adjust scopes as needed
            'state' => $state,
        ];

        $url = 'https://accounts.google.com/o/oauth2/auth?' . http_build_query($params);
        return redirect($url);
    }

    public function handleGoogleCallback(Request $request)
    {
        $state = $request->get('state');
        $storedState = session('google_state');

        if (!$state || $state !== $storedState)
            return redirect('/login')->with('error', 'Invalid Google state');
        
        $code = $request->get('code');
        $this->accessToken = $this->getAccessToken($code);

        $userInfo = $this->getUserInfo();
        try {
            $user = User::where('email', $userInfo['email'])->first();
            if(!$user)
            $user = new User();

            $user->name  = $userInfo['name'];
            $user->email = $userInfo['email'];
            $user->password = Hash::make($userInfo['email']);
            $user->photo  = $userInfo['picture'];
            $user->save();
            $request->session()->put('admin_login',true);
            $request->session()->put('admin_id',$user->id);
            return redirect()->route('admin.dashboard');
        } catch (\Exception $e) {
            return redirect('/login')->with('error', $e->getmessage());
        }
    }

    private function getAccessToken($code)
    {
        $url = 'https://oauth2.googleapis.com/token';
        $params = [
            'code' => $code,
            'client_id' => config('services.google.client_id'),
            'client_secret' => config('services.google.client_secret'),
            'redirect_uri' =>config('services.google.redirect'),
            'grant_type' => 'authorization_code',
        ];

        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($params));
        $response = curl_exec($ch);
        curl_close($ch);

        $data = json_decode($response, true);

        return $data['access_token'] ?? null;
    }

    public function getUserInfo()
    {
        $url = 'https://www.googleapis.com/oauth2/v3/userinfo';

        $headers = [
            'Authorization: Bearer ' . $this->accessToken,
        ];

        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

        $response = curl_exec($ch);
        curl_close($ch);

        return json_decode($response, true);
    }


    public function redirectToFacebook()
    {
        $state = bin2hex(random_bytes(16));
        session(['facebook_state' => $state]);

        $params = [
            'client_id' => config('services.facebook.client_id'),
            'redirect_uri' => config('services.facebook.redirect'),
            'state' => $state,
            'scope' => 'email', // Adjust scopes as needed
            'response_type' => 'code',
        ];

        $url = 'https://www.facebook.com/v18.0/dialog/oauth?' . http_build_query($params);
        return redirect($url);
    }

    public function handleFacebookCallback(Request $request)
    {
        $state = $request->get('state');
        $storedState = session('facebook_state');

        if (!$state || $state !== $storedState) {
            return redirect('/login')->with('error', 'Invalid Facebook state');
        }

        $code = $request->get('code');
        $accessToken = $this->getFacebookAccessToken($code);
        $userInfo = $this->getFacebookUserInfo($accessToken);

        try {
            $user = User::where('email', $userInfo['email'])->first();

            if (!$user) {
                $user = new User();
                $user->name = $userInfo['name'];
                $user->email = $userInfo['email'];
                $user->password = Hash::make($userInfo['email']);
                $user->photo = $userInfo['picture'];
                $user->save();
            }

            $request->session()->put('user_login', true);
            $request->session()->put('user_id', $user->id);

            return redirect('/home');
        } catch (\Exception $e) {
            return redirect('/login')->with('error', $e->getMessage());
        }
    }

    private function getFacebookAccessToken($code)
    {
        $url = 'https://graph.facebook.com/v12.0/oauth/access_token';
        $params = [
            'code' => $code,
            'client_id' => config('services.facebook.client_id'),
            'client_secret' => config('services.facebook.client_secret'),
            'redirect_uri' => config('services.facebook.redirect'),
        ];

        $ch = curl_init($url . '?' . http_build_query($params));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $response = curl_exec($ch);
        curl_close($ch);

        $data = json_decode($response, true);

        return $data['access_token'] ?? null;
    }

    private function getFacebookUserInfo($accessToken)
    {
        $url = 'https://graph.facebook.com/v12.0/me?fields=id,name,email,picture';

        $headers = [
            'Authorization: Bearer ' . $accessToken,
        ];

        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

        $response = curl_exec($ch);
        curl_close($ch);

        return json_decode($response, true);
    }

    public function redirectToLinkedIn()
    {
        $state = bin2hex(random_bytes(16));
        session(['linkedin_state' => $state]);

        $params = [
            'response_type' => 'code',
            'client_id' => config('services.linkedin.client_id'),
            'redirect_uri' => config('services.linkedin.redirect'),
            'state' => $state,
            'scope' => 'r_liteprofile r_emailaddress',
        ];

        $url = 'https://www.linkedin.com/oauth/v2/authorization?' . http_build_query($params);
        return redirect($url);
    }

    public function handleLinkedInCallback(Request $request)
    {
        $state = $request->get('state');
        $storedState = session('linkedin_state');

        if (!$state || $state !== $storedState) {
            return redirect('/login')->with('error', 'Invalid LinkedIn state');
        }

        $code = $request->get('code');
        $accessToken = $this->getLinkedInAccessToken($code);
        $userInfo = $this->getLinkedInUserInfo($accessToken);

        try {
            $user = User::where('email', $userInfo['email'])->first();

            if (!$user) {
                $user = new User();
                $user->name = $userInfo['name'];
                $user->email = $userInfo['email'];
                $user->password = Hash::make($userInfo['email']); // Use a secure way to generate a password
                // Additional user data from LinkedIn can be saved here
                $user->save();
            }

            // Set user session or perform authentication as needed
            $request->session()->put('user_login', true);
            $request->session()->put('user_id', $user->id);

            return redirect('/home');
        } catch (\Exception $e) {
            return redirect('/login')->with('error', $e->getMessage());
        }
    }

    private function getLinkedInAccessToken($code)
    {
        $url = 'https://www.linkedin.com/oauth/v2/accessToken';
        $params = [
            'grant_type' => 'authorization_code',
            'code' => $code,
            'client_id' => config('services.linkedin.client_id'),
            'client_secret' => config('services.linkedin.client_secret'),
            'redirect_uri' => config('services.linkedin.redirect'),
        ];

        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($params));

        $response = curl_exec($ch);
        curl_close($ch);

        $data = json_decode($response, true);

        return $data['access_token'] ?? null;
    }

    private function getLinkedInUserInfo($accessToken)
    {
        $url = 'https://api.linkedin.com/v2/me';
        $headers = [
            'Authorization: Bearer ' . $accessToken,
        ];

        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

        $response = curl_exec($ch);
        curl_close($ch);

        return json_decode($response, true);
    }

}
