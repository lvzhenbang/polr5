<?php

namespace App\Http\Controllers;

use Laravel\Socialite\Facades\Socialite;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
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
            
Log::info($driver . ' user logged in: ' . $driverUser->name);
            $user = User::updateOrCreate(
                ['driver_id' => $driverUser->id ],
                [
                    'name' => $driverUser->name,
                    'email' => $driverUser->email,
                    'driver' => $driver,
                    'driver_token' => $driverUser->token,
                    'driver_refresh_token' => $driverUser->refreshToken,
                ]
            );
            
            $request->session()->put('username', $user->username);
            $request->session()->put('role', $user->role);

            return redirect(route('index'))->with('success', 'Login successfully.');

        } catch (\Exception $e) {
            return redirect('/login')->withErrors([
                'socialite' => $driver .'login fail: '.$e->getMessage()
            ]);
        }
    }
}