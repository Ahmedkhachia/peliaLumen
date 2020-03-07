<?php

namespace App\Http\Controllers\Patient;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Mesure;
use App\User;
use Carbon\Carbon;
use App\Mesure_users;
use Auth;

class MesureController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
////////////////Get all mesures 
    public function index(){
        $liste_mesures = new Mesure();
        $getListe = $liste_mesures->getMesures(Auth::user()->id);
        return response()->json($getListe, 200);
    }

////////////////Add a mesure 
    public function store(Request $request){
        $Mesure_users = new Mesure_users;
        $Mesure_users->id_mesures = $request->id_mesures;
        $Mesure_users->id_user = Auth::user()->id;
        $Mesure_users->value = $request->value;
        $Mesure_users->save();
        return response()->json($medicament, 200);

    }

////////////////edit function 
    public function edit($id){
        $Mesure_users = Mesure_users::where("id_mesures", $id)->first();
        $getVerify = new VerifyPolicy;
        if($getVerify->VerifyMesures($Mesure_users)){
            $User->nom_mesure = $request ->nom;
            $User->unite = $request->unite;
            $User->save();
            return response()->json($Mesure_users, 200);
        }else{
            return response()->json("Cette action n'est pas autorisée", 403);
        }
      
    }

////////////////update a mesure
    public function update(Request $request, $id){
        $Mesure_users = Mesure_users::where("id_mesures", $id)->first();
        $getVerify = new VerifyPolicy;
        if($getVerify->VerifyMesures($Mesure_users)){
            $Mesure_users->id_mesures = $request->id_mesures;
            $Mesure_users->value = $request->value;
            $Mesure_users->save();
            return response()->json($Mesure_users, 200);
        }else{
            return response()->json("Cette action n'est pas autorisée", 403);
        }
    }

////////////////Delete a mesures 
    public function destroy(Request $request, $id){
        $Mesure_users = Mesure_users::where("id_mesures", $id)->first();
        $getVerify = new VerifyPolicy;
        if($getVerify->VerifyMesures($Mesure_users)){
            $Mesure_users->delete();
            return response()->json("La mesure a été supprimé avec succès", 200);
        }else{
            return response()->json("Cette action n'est pas autorisée", 403);
        }
    }
}

