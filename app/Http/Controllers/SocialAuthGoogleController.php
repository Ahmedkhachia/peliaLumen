<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use Socialite;
use Auth;
use Exception;

class SocialAuthGoogleController extends Controller
{
    public function redirect()
    {
        return Socialite::driver('google')->redirect();
    }


    public function callback()
    {
        try {
            
            
            $googleUser = Socialite::driver('google')->stateless()->user();
            
            $existUser = User::where('email',$googleUser->email)->first();
            

            if($existUser) {
                Auth::loginUsingId($existUser->id);
                if(session()->get('type') == 1){
                    return redirect('dashboard');
                }
                elseif(session()->get('type') == 2){
                    return redirect('inscrire');
                }
            }
            else {
                $user = new User;
                $user->nom = $googleUser->name;
                $user->email = $googleUser->email;
                $user->google_id = $googleUser->id;
                $user->Type = session()->get('type');
                $user->password = md5(rand(1,10000));
                $user->save();
                Auth::loginUsingId($user->id);
                if(session()->get('type') == 1){
                    return redirect('dashboard');
                }
                elseif(session()->get('type') == 2){
                    return redirect('inscrire');
                }
            }
            
            
            
        } 
        catch (Exception $e) {
            return $e;
        }
    }
}
