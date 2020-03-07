<?php

namespace App\Http\Controllers\Patient;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Specialite;
use App\Ville;
use App\Medecins;
use App\medecin_specialite;
use App\Mesure;
use App\User;
use App\Medicament;
use Auth;

class SearchMedecinController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('isActive');
    }

    public function index(){
        $getSpecialite = Specialite::all();
        $getVille = Ville::all();
        return view("patients.rechercheMedecins.index", ["specialite" =>$getSpecialite, "ville" => $getVille]);
    }

    public function getDataSearch(Request $request){
        $get = new Medecins;
        $getInfo = $get->getSearchMedecin($request->nom_medecin ,$request->specialiteSelect, $request->villeSelect);
        // return dd($getInfo);
         return view("patients.rechercheMedecins.liste", ["Liste" => $getInfo]);
    }

    public function profilMedecin($id){

        if(Auth::user()->id_type == 1){
            $type = "medecin";
            $Medecin = new Medecins();
            $medecin_spe = new medecin_specialite();
            $MedecinInfo = $Medecin->getInfoMedecin($id);
            $specialite = $medecin_spe->getSpecialite($id);
            return view("patients.ListeMedecin.profilMedecin", ["info" => $MedecinInfo, "specialite" => $specialite]);
            // return dd($specialite->toArray());
        }
        elseif(Auth::user()->id_type == 2){
            $User = new User();
            $getUser = $User->getUser($id);
            $User_medicament = new Medicament();
            $User_mesures = new Mesure();
            $User_observance = $User_medicament->getDataObservance($id);
            $User_name = $User->name($id);
            $prenom= $User_name[0]->prenom."  ".$User_name[0]->nom;
            $medicament = $User_medicament->get_medicament();
            $getMesure = $User_mesures->getMesures($id);
             return view("medecins.ProfilPatient.index", ['medicament' => $medicament, 'observance'=> $User_observance, 'User' => $getUser, 'mesures' => $getMesure, 'prenom' => $prenom]);
        }
    }
}
