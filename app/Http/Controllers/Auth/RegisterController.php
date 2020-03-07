<?php

namespace App\Http\Controllers\Auth;

use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\Model;
use Laravel\Lumen\Routing\Controller as BaseController;
use lluminate\Support\MessageBag;
use App\Providers\RouteServiceProvider;
use Tymon\JWTAuth\Facades\JWTAuth;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Tymon\JWTAuth\Exceptions\JWTException;
Use App\User;
use Illuminate\Support\Facades\Validator;
use App\VilleUsers;

class RegisterController extends BaseController
{
    /*
    |--------------------------------------------------------------------------
    | Register Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users as well as their
    | validation and creation. By default this controller uses a trait to
    | provide this functionality without requiring any additional code.
    |
    */

    // use RegistersUsers;

    /**
     * Where to redirect users after registration.
     *
     * @var string
     */
  
    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return \App\User
     */

// Methode de register avant la periode d'essaye
    public function create(Request  $request){
        $dataForm = $request->all();
        $errors = [];
        $rules =[
        'nom'=>'required|min:3|max:100',
        'prenom'=>'required|min:3|max:100',
        'phone' => ['required', 'string'],
        ];
    
        $valida = validator($dataForm, $rules);
        if(count($valida->errors()->all()) == 0)
        {
            $user =  new User; 
            $user->prenom = $request->prenom;
            $user->nom = $request->nom;
            $user->id_type = 1;
            $user->phone = $request->phone;
            $user->save();
            // $credentials = $request->only(['email', 'password']);

            $user=User::where('phone', $request->phone)->first();

            if (! $token = JWTAuth::fromUser($user)) {
                return response()->json(['error' => 'Unauthorized'], 401);
            }
                Auth::login($user);
                return $this->respondWithToken($token);
                
        }
        else
        {
                return $valida->errors()->toArray()['email'][0]; 
        } 
         
    }

// Methode de register après la periode d'essaye

    public function store(Request  $request, $id){
        $dataForm = $request->all();
        $errors = [];
        $rules =[
        'age'=>'required',
        'sex'=>['required', 'string'],
        'id_ville' => 'required',
        'password' => ['required', 'string'],
        ];
    
        $valida = validator($dataForm, $rules);
        if(count($valida->errors()->all()) == 0){
            $user =  User::where("id", $id)->first(); 
            $user->email = $request->email;
            $user->age = $request->age;
            $user->sex = $request->sex;
            $user->photo_utilisateur = $request->photo_utilisateur;
            $user->password = Hash::make($request['password']);
            $user->save();
            $ville = new VilleUsers;
            $ville->id_ville = $request->id_ville;
            $ville->id_user = $user->id;
            $ville->save();

        if (! $token = JWTAuth::fromUser($user)) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }
            Auth::login($user);
            return $this->respondWithToken($token);
        }
        else{
                return $valida->errors()->toArray(); 
        }   
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
        // $this->middleware('guest');
    }

}
