<?php

namespace App\Http\Controllers\Patient;

use Laravel\Lumen\Routing\Controller as BaseController;
use Illuminate\Http\Request;
use App\User;
use App\Medicament;
use App\Rendez_vous;
use App\Mesure;
use App\VilleUsers;
use Auth;

class ProfilPatientController extends BaseController
{
    
    public function index(){
        $getPatient = User::where("users.id", Auth::user()->id)->first();
        $getVille = VilleUsers::select("ville.id_ville", "ville_users.id_ville", "id_user", "nom_ville", "nom_ville_arab")
        ->where("id_user", Auth::user()->id)
        ->join("ville", "ville.id_ville", "=", "ville_users.id_ville")
        ->first();
        $getPatient->setAttribute("ville", $getVille);
        return response()->json($getPatient, 200);
    }
}
