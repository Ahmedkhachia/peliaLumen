<?php

namespace App\Http\Controllers\Auth;

use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\Model;
use Laravel\Lumen\Routing\Controller as BaseController;
use lluminate\Support\MessageBag;
use App\Providers\RouteServiceProvider;
use Tymon\JWTAuth\Facades\JWTAuth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Foundation\Auth\ThrottlesLogins;
use Illuminate\Foundation\Auth\AuthenticatesAndRegistersUsers;
use Tymon\JWTAuth\Exceptions\JWTException;
Use App\User;
use Illuminate\Support\Facades\Validator;

class LoginController extends BaseController
{
  
        
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */
    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    public function login(Request $request){

        $user=User::where('phone', $request->phone)->first();

        if($user->password == Null){
            //validate incoming request 
            $this->validate($request, [
                'phone' => 'required|string',
            ]);
            if (! $token = JWTAuth::fromUser($user)) {
                return response()->json(['error' => "L'email ou bien le mot de passe et incorrect"], 401);
            }
            Auth::login($user);
            return $this->respondWithToken($token);
        }
        else
        {
            //validate incoming request 
            $this->validate($request, [
                'phone' => 'required|string',
                'password' => 'required|string',
            ]);
            $credentials = $request->only(['phone', 'password']);

            if (! $token = Auth::attempt($credentials)) {
                return response()->json(['error' => "Le numéro de téléphone ou bien le mot de passe et incorrect"], 401);
            }
            return $this->respondWithToken($token); 
        }
           
    }

 /**
     * Get the authenticated User.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function me()
    {
        return response()->json(Auth::user());
    }

    /**
     * Log the user out (Invalidate the token).
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function logout()
    {
        auth()->logout();

        return response()->json(['message' => 'Successfully logged out']);
    }

    /**
     * Refresh a token.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function refresh()
    {
        return $this->respondWithToken(auth()->refresh());
    }

    
    /**
     * Get the token array structure.
     *
     * @param  string $token
     *
     * @return \Illuminate\Http\JsonResponse
     */
    protected function respondWithToken($token)
    {
        
        return response()->json([
            'token' => $token,
            'token_type' => 'bearer',
            'expires_in' => Auth::factory()->getTTL() * 60,
            'user' => Auth::user()
        ]);
    }
    

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        // $this->middleware('api');
        // $this->middleware('guest')->except('logout');
        
    }
}
