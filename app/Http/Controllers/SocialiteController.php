<?php

namespace App\Http\Controllers;

use Laravel\Socialite\Facades\Socialite;
use App\Helpers\UserHelper;
use App\Helpers\CryptoHelper;
use App\Factories\UserFactory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class SocialiteController extends Controller
{
    public function redirect(Request $request, string $driver)
    {
        return Socialite::driver($driver)->redirect();
    }

    public function callback(Request $request, string $driver)
    {
        try {
            $driverUser = Socialite::driver($driver)->user();
            
            $ip = $request->ip();

            $user_driver_id_exists = UserHelper::userDriverIdExists($driverUser->id);

            if (!$user_driver_id_exists) {
                $api_active = false;
                $api_key = null;

                if (env('SETTING_AUTO_API')) {
                    $api_active = 1;
                    $api_key = CryptoHelper::generateRandomHex(env('_API_KEY_LENGTH'));
                }

                $user = UserFactory::createSocialUser($driverUser->name, $driverUser->email, $ip, $api_key, $api_active, false, $driver, $driverUser->id, $driverUser->token, $driverUser->refreshToken);                
                Log::info('New social user created: ' . $user->username . ' via ' . $driver);
            }
            
            $request->session()->put('username', $driverUser->name);
            $request->session()->put('driverid', $driverUser->id);
            $request->session()->put('role', 'default');   
            return redirect(route('index'))->with('success', 'Login successfully.');

        } catch (\Exception $e) {
            return redirect('/login')->withErrors([
                'socialite' => $driver . ' login fail: ' . $e->getMessage()
            ]);
        }
    }
}